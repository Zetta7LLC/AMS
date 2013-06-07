/*
Copyright 2013 Zetta7 LLC - Bellevue, WA

This file is part of AMS (Android Monitoring Service) created by Zetta7 LLC.

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
function sendAjaxGetRequest(url, callbackfunc){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			callbackfunc(xmlhttp.responseText);
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function changeApplication(valOfApplicaiton)
{
    if(valOfApplicaiton != 0)
    {
        createCookie("amsApplication", valOfApplicaiton, 10);
        location.reload();
    }
}

function amsGraph1(target){
    clearTarget(target);
    var s1 = [200, 600, 700, 1000];
    var s2 = [460, -210, 690, 820];
    var s3 = [-260, -440, 320, 200];
    // Can specify a custom tick Array.
    // Ticks should match up one for each y value (category) in the series.
    var ticks = ['May', 'June', 'July', 'August'];
    var plot1 = $.jqplot(target, [s1, s2, s3], {
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {fillToZero: true}
        },
        // Custom labels for the series are specified with the "label"
        // option on the series option.  Here a series option object
        // is specified for each series.
        series:[
            {label:'Hotel'},
            {label:'Event Regristration'},
            {label:'Airfare'}
        ],
        // Show the legend and put it outside the grid, but inside the
        // plot container, shrinking the grid to accomodate the legend.
        // A value of "outside" would not shrink the grid and allow
        // the legend to overflow the container.
        legend: {
            show: true,
            placement: 'outsideGrid'
        },
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: ticks
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
                pad: 1.05,
                tickOptions: {formatString: '$%d'}
            }
        }
    });
}


function amsGraph2(target){
  clearTarget(target);
  var s1 = [2, 6, 7, 10];
  var s2 = [7, 5, 3, 4];
  var s3 = [14, 9, 3, 8];
  plot3 = $.jqplot(target, [s1, s2, s3], {
    // Tell the plot to stack the bars.
    stackSeries: true,
    captureRightClick: true,
    seriesDefaults:{
      renderer:$.jqplot.BarRenderer,
      rendererOptions: {
          // Put a 30 pixel margin between bars.
          barMargin: 30,
          // Highlight bars when mouse button pressed.
          // Disables default highlighting on mouse over.
          highlightMouseDown: true   
      },
      pointLabels: {show: true}
    },
    axes: {
      xaxis: {
          renderer: $.jqplot.CategoryAxisRenderer
      },
      yaxis: {
        // Don't pad out the bottom of the data range.  By default,
        // axes scaled as if data extended 10% above and below the
        // actual range to prevent data points right on grid boundaries.
        // Don't want to do that here.
        padMin: 0
      }
    },
    legend: {
      show: true,
      location: 'e',
      placement: 'outside'
    }      
  });
  // Bind a listener to the "jqplotDataClick" event.  Here, simply change
  // the text of the info3 element to show what series and ponit were
  // clicked along with the data for that point.
  $(target).bind('jqplotDataClick', 
    function (ev, seriesIndex, pointIndex, data) {
      $('#info2').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
    }
  ); 
}


function amsGraph3(target){
     clearTarget(target);
    var arr = [[11, 123, 1236, "Acura"], [45, 92, 1067, "Alfa Romeo"], 
    [24, 104, 1176, "AM General"], [50, 23, 610, "Aston Martin Lagonda"], 
    [18, 17, 539, "Audi"], [7, 89, 864, "BMW"], [2, 13, 1026, "Bugatti"]];
     
    var plot2 = $.jqplot(target,[arr],{
        title: 'Transparent Bubbles',
        seriesDefaults:{
            renderer: $.jqplot.BubbleRenderer,
            rendererOptions: {
                bubbleAlpha: 0.6,
                highlightAlpha: 0.8
            },
            shadow: true,
            shadowAlpha: 0.05
        }
    });    
}


function piChart(target, data, title){
    clearTarget(target);

  var plot1 = jQuery.jqplot (target, [data], 
    { 
      title: title,
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
}

function amsGraph5(target){
  clearTarget(target);
  var plot2 = $.jqplot (target, [[3,7,9,1,4,6,8,2,5]], {
      // Give the plot a title.
      title: 'Plot With Options',
      // You can specify options for all axes on the plot at once with
      // the axesDefaults object.  Here, we're using a canvas renderer
      // to draw the axis label which allows rotated text.
      axesDefaults: {
        labelRenderer: $.jqplot.CanvasAxisLabelRenderer
      },
      // An axes object holds options for all axes.
      // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
      // Up to 9 y axes are supported.
      axes: {
        // options for each axis are specified in seperate option objects.
        xaxis: {
          label: "X Axis",
          // Turn off "padding".  This will allow data point to lie on the
          // edges of the grid.  Default padding is 1.2 and will keep all
          // points inside the bounds of the grid.
          pad: 0
        },
        yaxis: {
          label: "Y Axis"
        }
      }
    });
}
function amsGraph6(target){
  clearTarget(target);
  var cosPoints = []; 
  for (var i=0; i<2*Math.PI; i+=0.1){ 
     cosPoints.push([i, Math.cos(i)]); 
  } 
  var plot1 = $.jqplot(target, [cosPoints], {  
      series:[{showMarker:false}],
      axes:{
        xaxis:{
          label:'Angle (radians)'
        },
        yaxis:{
          label:'Cosine'
        }
      }
  });
}
function barGraph(target, data, graphTitle){
  clearTarget(target);

      var plot1b = $.jqplot(target, [data], {
        title: graphTitle,
        series:[{renderer:$.jqplot.BarRenderer}],
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
            labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
            tickOptions: {
              labelPosition: 'middle', 
              angle:-30,
              fontSize: '10pt'
            }
        },
        axes: {
          xaxis: {
            tickOptions:{ 
                angle: -30,
                labelPosition: 'middle', 
            },
            renderer: $.jqplot.CategoryAxisRenderer,
    }    }
      });
}

function lineGraph(target, line1, graphName){
  clearTarget(target);

  var plot1 = $.jqplot(target, [line1], {
      title:graphName,
      axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
            formatString:'%b&nbsp;%#d'
          } 
        },
        yaxis:{
          tickOptions:{
            }
        }
      },
      highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor: {
        show: false
      }
  });
}

function clearTarget(target){
  document.getElementById(target).innerHTML = "";
}

/*****PAGE LOAD*****/
$(document).ready(function(){
    //select an application box
    if(document.getElementById("selApplications"))
    { //we have multiple applications
        var appId = readCookie("amsApplication");
        if(appId != null && appId != "")
        { //select an option in the dropdown
            var arrOptions = document.getElementById("selApplications").getElementsByTagName("option");
            for(var i = 0; i < arrOptions.length; i++)
            {
                if(arrOptions[i].value == appId) //the option in the dropdown has the same value as the appId in the cookie
                {
                    arrOptions[i].selected = true;
                }
            }
        }
    }
});