/*
	Copyright 2013 Zetta7 LLC - Bellevue, WA

	This file is part of AMS (Application Monitoring Service) created by Zetta7 LLC.

    AMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    AMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
 	along with AMS.  If not, see <http://www.gnu.org/licenses/>.
*/

package com.zetta7;

import android.annotation.SuppressLint;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.provider.Settings.Secure;
import android.util.Log;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Enumeration;
import java.util.LinkedList;
import java.util.List;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;

/*
 * AMS Library Implementation 
 */
public class AMSLibrary {
	private String clientID;				//customer's id
	private String deviceID;				//device id
	private List<AppActivity> lstActivity;  //collection of AppActivity
	private Context myContext;				//Android context
	private int sdkVersion;					//device's SKD version
	private String model;					// Model 
	private final String platform = "android";	// IP Address
	private String serverURL; 				//server's url. Ex: "http://www.zetta7.com/ams/webservice/";
	private boolean isEnabled;				//switch to enable / disable sending requests to server
	private HTTPManager reqManager = null;  //instance of HTTPManager
	
	//constructor
	public AMSLibrary(Context c, String clientID, String endpoint){
		this.clientID = clientID;
		this.myContext = c;
		this.serverURL = endpoint;
		this.deviceID = Secure.getString(this.myContext.getContentResolver(), Secure.ANDROID_ID);
		this.sdkVersion = android.os.Build.VERSION.SDK_INT;
		this.lstActivity = new LinkedList<AppActivity>();
		this.model = android.os.Build.MODEL;
		
		if(this.myContext != null && this.serverURL != ""){
			this.reqManager = new HTTPManager(this.myContext, this.serverURL);
		}
		this.isEnabled = true;
	}

	//Main method to log an activity
	public void logActivity(AppActivity curActivity){
		//build XML and send it to web server
		if(this.reqManager != null && this.isEnabled && isValid(curActivity)){
			String xmlData = ActivityToXML(curActivity);
			this.reqManager.Send(xmlData);
		}
	}
	//Allows clients to add activities to the list without sending them to the server
	//with intention to log them later 
	public void AddActivity(AppActivity actv){
		if(this.lstActivity == null){
			this.lstActivity = new LinkedList<AppActivity>();
		}
		//Only save valid activities
		if(isValid(actv)){
			this.lstActivity.add(actv);
		}
	}
	//Allows clients to log previously saved activities
	public void logAllActivities(){
		if(this.reqManager != null && this.isEnabled && this.lstActivity != null){
			String xmlData = ActivitiesToXML();
			this.reqManager.Send(xmlData);
		}
	}
	//Produces XML string based on activity
	@SuppressLint("SimpleDateFormat")
	private String ActivityToXML(AppActivity actv){
		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
		Date date = new Date();
		StringBuilder sb = new StringBuilder();
		sb.append("<?xml version=\"1.0\" encoding=\"UTF-8\"?><AMSRequest>");
		sb.append("<AppKey>" + this.clientID + "</AppKey>");
		sb.append("<DeviceId>" + this.deviceID + "</DeviceId>");
		sb.append("<DeviceSDK>" + this.sdkVersion + "</DeviceSDK>");
		sb.append("<Platform>" + this.platform + "</Platform>");
		sb.append("<DeviceModel>" + this.model + "</DeviceModel>");
		sb.append("<TimeStamp>" + dateFormat.format(date) + "</TimeStamp><AppActivity>");
		sb.append("<TypeId>" + actv.typeID + "</TypeId>");
		sb.append("<TypeName>" + getTypeName(actv.typeID) + "</TypeName>");
		sb.append("<Message>" + actv.message + "</Message>");
		sb.append("<Description>" + actv.description + "</Description>");
		sb.append("<Notes>" + actv.notes + "</Notes>");
		sb.append("</AppActivity></AMSRequest>");
		return sb.toString();
	}
	//Produces XML string based on collection of activities
	private String ActivitiesToXML(){
		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
		Date date = new Date();
		StringBuilder sb = new StringBuilder();
		sb.append("<?xml version=\"1.0\" encoding=\"UTF-8\"?><AMSRequest>");
		sb.append("<ClientId>" + this.clientID + "</ClientId>");
		sb.append("<DeviceId>" + this.deviceID + "</DeviceId>");
		sb.append("<DeviceSDK>" + this.sdkVersion + "</DeviceSDK>");
        sb.append("<Platform>" + this.platform + "</Platform>");
        sb.append("<DeviceModel>" + this.model + "</DeviceModel>");
		sb.append("<TimeStamp>" + dateFormat.format(date) + "</TimeStamp>");
		for(int i = 0; i < this.lstActivity.size(); i++){
			AppActivity actv = this.lstActivity.get(i);
			sb.append("<AppActivity><TypeId>" + actv.typeID + "</TypeId>");
			sb.append("<TypeName>" + getTypeName(actv.typeID) + "</TypeName>");
			sb.append("<Message>" + actv.message + "</Message>");
			sb.append("<Description>" + actv.description + "</Description>");
			sb.append("<Notes>" + actv.notes + "</Notes></AppActivity>");
		}
		sb.append("</AMSRequest>");
		return sb.toString();
	}
	//checks if activity is valid
	private boolean isValid(AppActivity actv){
		if(actv == null){
			return false;
		}
		return true;
	}
	//returns type of activity based on activity id
	private String getTypeName(int typeId){
		if(typeId == 1){
			return "success";
		}else if(typeId == 3){
			return "warning";
		}else if(typeId == 4){
			return "info";
		}else{
			return "error";
		}
		
	}
	//Describes activity
	public static class AppActivity{
		public int typeID;
		public String message;
		public String description;
		public String notes;
	}
	//Describes activity type
	public static class AppActivityType{
		public final int SUCCESS = 1;
		public final int ERROR = 2;
		public final int WARNING = 3;
		public final int INFO = 4;
	}
	
	private class HTTPManager {
		private String URL = "";
		private Context context = null;
		
		public HTTPManager(Context c, String url)
		{
			this.URL = url;
			this.context = c;
		}
	    
	    private boolean isDeviceOnline()
	    {
	    	try{
		    	ConnectivityManager connMgr = (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);
		    	if(connMgr == null)
		    		return false;
			    NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
			    NetworkInfo mWifi = connMgr.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
			    if(mWifi != null && mWifi.isConnected()){
			    	return true; 
			    }else if (networkInfo != null && networkInfo.isConnected()){
			    	return true;
			    }else{
			    	return false;
			    }
	    	}catch(Exception ex){
	    		return false;
	    	}
	    }
	    
		public AsyncTask<String, Void, String> SendAsyncRequest(final String dataToSend)
		{
			String[] arrUrl = {dataToSend};
			return new AsyncTask<String, Void, String>()
			{
				InputStream content = null;
				String responseMsg = "";
		   		protected String doInBackground(String... arrUrl) 
		   		{
		   			if(isDeviceOnline())
		   			{
		   		    	try 
		   		    	{
		   		    		HttpClient httpclient = new DefaultHttpClient();
		   		    		HttpPost httppost = new HttpPost(URL);
		   		    		StringEntity se = new StringEntity(dataToSend);
		   		    		httppost.setEntity(se);
		   		    		HttpResponse response = httpclient.execute(httppost);
		   		    		response.getStatusLine().getStatusCode();
		   		    		content = response.getEntity().getContent();
		   		    		BufferedReader reader = new BufferedReader(new InputStreamReader(content,"iso-8859-1"),8);
		   		    		String line=null;
		   		    		StringBuilder sbResponse = new StringBuilder();
							while ((line = reader.readLine()) != null) 
							{
								sbResponse.append(line);
							}
							responseMsg = sbResponse.toString();
							Log.w("SendAsyncRequest - responseMsg", responseMsg);
		   		    	} 
		   		    	catch (ClientProtocolException e) 
		   		    	{
		   		        } 
		   		    	catch (IOException e) 
		   		    	{
		   		    	}
		   			}
		   			return responseMsg;
		   		}
			}.execute(arrUrl);
		}
		
		public AsyncTask<?, ?, ?> Send(String XMLMessage)
		{
			return SendAsyncRequest(XMLMessage);
		}
	}
}