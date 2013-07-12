<!--
You are free to copy and use this sample in accordance with the terms of the
Apache license (http://www.apache.org/licenses/LICENSE-2.0.html)
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1');
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                  function drawVisualization(output) {
                    var wrapper = new google.visualization.ChartWrapper({
                      chartType: 'ColumnChart',
                      dataTable: [['', '99', '98', '97', '96', '95', '94', '93', '92', '91', '90', '89', '88'],
                                  ['', parseInt(output[1]), parseInt(output[2]), parseInt(output[3]), parseInt(output[4]), parseInt(output[5]), parseInt(output[6]), 
                                       parseInt(output[7]), parseInt(output[8]), parseInt(output[9]), parseInt(output[10]), parseInt(output[11]), parseInt(output[12])]],
                      options: {'title': 'Distribution By Grade'},
                      containerId: 'visualization'
                    });
                    wrapper.draw();
                  }
      
                  
                  $.post(
                    'GetGradeTotals.php',
                        { empID: this.name, change: this.id, value: this.value
                        }, function( data ){
                            output = data.split(',');
                            google.setOnLoadCallback(drawVisualization(output));
                        }
                  );       
            });
    </script>
    <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen"></link>
    <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen"></link>
    <title>Organizational Shape</title>
    
  </head>
  <body style="font-family: Arial;border: 0 none;">
      <h1>Reports: Organizational Shape</h1>
       <?php
            if(!class_exists('Reports')){
                include 'Reports.php';
            }
            $rep = new Reports();
            $rep->displayReportsMenu("ReportsWeb");

            unset($rep);
       ?>
        <div id="visualization" style="float:left; width: 800px; height: 600px;"></div>
  </body>
</html>
​