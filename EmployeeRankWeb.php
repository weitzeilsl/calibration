<!--
 * File:   EmployeeRankWeb
 * Author: Steven Weitzeil
 * Date:   6 April 2013
 * Desc:   Display and manage the Employee Rank page
 -->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>     
            var $oldValue;
            var $newValue;
            var $column;

            $(document).ready(function(){
                $("input").keyup(function(e){
                    if(e.keyCode === 9  ||   //Don't calc if: Tab
                       e.keyCode === 37 ||   //Left
                       e.keyCode === 38 ||   //Up
                       e.keyCode === 39 ||   //Right
                       e.keyCode === 40)     //Down
                        return;
                    if(this.value.length > 1) {
                        this.value = this.value.substr(1,1);
                    }
                    if(!this.value.length || isNaN(this.value)){
                        this.value = 3;
                        this.select();
                    }
                    if(this.value >5) {
                        this.value = 5;
                        this.select();
                    } else if (this.value < 1) {
                        this.value = 1;
                        this.select();
                    }
                    $newValue = this.value;
                    
                    var focusRow = $(this).closest('tr');
                    if(this.id.substr(0,4) === "Romp") {
                        var value1, value2, value3, value4, value5, value6, value7, value8, value9;

                        value1 = $(focusRow).find("td.Romp1 input:text").val();
                        value2 = $(focusRow).find("td.Romp2 input:text").val();
                        value3 = $(focusRow).find("td.Romp3 input:text").val();
                        value4 = $(focusRow).find("td.Romp4 input:text").val();
                        value5 = $(focusRow).find("td.Romp5 input:text").val();
                        value6 = $(focusRow).find("td.Romp6 input:text").val();
                        value7 = $(focusRow).find("td.Romp7 input:text").val();
                        value8 = $(focusRow).find("td.Romp8 input:text").val();
                        value9 = $(focusRow).find("td.Romp9 input:text").val();

                        $.post(
                        'UpdateRankTotals.php',
                            { empID: this.name, change: this.id, value: this.value, 
                              value1: value1, value2: value2, value3: value3, 
                              value4: value4, value5: value5, value6: value6, 
                              value7: value7, value8: value8, value9: value9 
                            }, function( total ){
                               $(focusRow).find("td.RankTotal").html(total);
                            }
                        );
                    }
                    //alert("oldValue:" + $oldValue +" newValue:" + $newValue);
                    if($oldValue !== $newValue){1
                        switch($oldValue) {
                            case "5":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp1-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp1-5").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp2-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp2-5").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp3-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp3-5").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp4-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp4-5").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp5-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp5-5").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp6-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp6-5").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp7-5').text();
                                        if(oldCount >0) {oldCount--;}
                                        $(thisRow).find("td.romp7-5").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp8-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp8-5").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp9-5').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp9-5").html(oldCount);
                                        break;
                                }
                                break;
                            case "4":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp1-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp1-4").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp2-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp2-4").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp3-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp3-4").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp4-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp4-4").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp5-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp5-4").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp6-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp6-4").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp7-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp7-4").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp8-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp8-4").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp9-4').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp9-4").html(oldCount);
                                        break;
                                }
                                break;
                            case "3":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp1-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp1-3").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp2-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp2-3").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp3-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp3-3").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp4-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp4-3").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp5-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp5-3").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp6-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp6-3").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp7-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp7-3").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp8-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp8-3").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp9-3').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp9-3").html(oldCount);
                                        break;
                                }
                                break;
                            case "2":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp1-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp1-2").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp2-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp2-2").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp3-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp3-2").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp4-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp4-2").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp5-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp5-2").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp6-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp6-2").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp7-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp7-2").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp8-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp8-2").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp9-2').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp9-2").html(oldCount);
                                        break;
                                }
                                break;
                            case "1":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp1-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp1-1").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp2-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp2-1").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp3-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp3-1").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp4-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp4-1").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp5-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp5-1").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp6-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp6-1").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp7-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp7-1").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp8-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp8-1").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp9-1').text();
                                        if(oldCount > 0) {oldCount--;}
                                        $(thisRow).find("td.romp9-1").html(oldCount);
                                        break;
                                }
                                break;
                        }

                        switch($newValue) {
                            case "5":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp1-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp1-5").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp2-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp2-5").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp3-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp3-5").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp4-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp4-5").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp5-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp5-5").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp6-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp6-5").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp7-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp7-5").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp8-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp8-5").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.fives');
                                        oldCount = $(thisRow).find('td.romp9-5').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp9-5").html(oldCount);
                                        break;
                                }
                                break;
                            case "4":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp1-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp1-4").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp2-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp2-4").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp3-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp3-4").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp4-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp4-4").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp5-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp5-4").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp6-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp6-4").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp7-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp7-4").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp8-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp8-4").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.fours');
                                        oldCount = $(thisRow).find('td.romp9-4').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp9-4").html(oldCount);
                                        break;
                                }
                                break;
                            case "3":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp1-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp1-3").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp2-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp2-3").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp3-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp3-3").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp4-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp4-3").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp5-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp5-3").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp6-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp6-3").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp7-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp7-3").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp8-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp8-3").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.threes');
                                        oldCount = $(thisRow).find('td.romp9-3').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp9-3").html(oldCount);
                                        break;
                                }
                                break;
                            case "2":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp1-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp1-2").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp2-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp2-2").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp3-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp3-2").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp4-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp4-2").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp5-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp5-2").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp6-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp6-2").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp7-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp7-2").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp8-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp8-2").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.twos');
                                        oldCount = $(thisRow).find('td.romp9-2').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp9-2").html(oldCount);
                                        break;
                                }
                                break;
                            case "1":
                                switch($column) {
                                    case "Romp1":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp1-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp1-1").html(oldCount);
                                        break;
                                    case "Romp2":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp2-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp2-1").html(oldCount);
                                        break;
                                    case "Romp3":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp3-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp3-1").html(oldCount);
                                        break;
                                    case "Romp4":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp4-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp4-1").html(oldCount);
                                        break;
                                    case "Romp5":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp5-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp5-1").html(oldCount);
                                        break;
                                    case "Romp6":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp6-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp6-1").html(oldCount);
                                        break;
                                    case "Romp7":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp7-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp7-1").html(oldCount);
                                        break;
                                    case "Romp8":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp8-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp8-1").html(oldCount);
                                        break;
                                    case "Romp9":
                                        thisRow = $(document).find('tr.ones');
                                        oldCount = $(thisRow).find('td.romp9-1').text();
                                        oldCount++;
                                        $(thisRow).find("td.romp9-1").html(oldCount);
                                        break;
                                }
                                break;
                        }
                        $oldValue = $newValue;
                    }

                });            
            });

            $(document).ready(function(){
                $("td input:text").focus(function() {
                    $oldValue = $(this).val();
                    curRow = $(this).closest('td');
                    $column = $(curRow).attr('class');
                });
            });  

            $(document).ready(function(){
                $('td input:checkbox').mousedown(function() {
                    if (!$(this).is(':checked')) {
                        $.post(
                        'UpdatePromotion.php',
                            { empID: this.name, value: 1 
                            }, function(){
                                $(this).prop('checked', true); // will check the checkbox
                            }
                        );
                    }  else {
                        $.post(
                        'UpdatePromotion.php',
                            { empID: this.name, value: 0 
                            }, function(){
                                $(this).prop('checked', false); // will uncheck the checkbox
                            }
                        );
                    }
                });
            });                                  
        </script>
        
        <title>Employee Rank</title>
        <link rel="stylesheet" href="css/rankMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/rankTable.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Employee Rank</h1>

        <?php
            gc_enable();
            
            //Validate that the current user is logged-in
            if (isset($_SESSION["name"])){
                $currentGrade = 0;    //All grades
                $currentRole = "All"; //All roles
                $directs = 0;         //With directs
                
                //Gather received parameters
                if(isset($_REQUEST['n'])){$encodedMgr = $_REQUEST['n'];}
                if(isset($_REQUEST['d'])){$directs = $_REQUEST['d'];}
                if(isset($_REQUEST['g'])){$currentGrade = $_REQUEST['g'];}
                if(isset($_REQUEST['r'])){$currentRole = $_REQUEST['r'];}
                if(isset($_REQUEST['s'])){$sortBy = $_REQUEST['s'];} else {$sortBy = 'lastName';}                
                
                //Get manager information
                if(!class_exists('ManagerData')) {
                    include 'ManagerData.php';
                }
                $managers = new ManagerData();
                $currentMgr = $managers->base64url_decode($encodedMgr);
                $_SESSION['selectedManager'] = $currentMgr;
                $mgrName = $managers->getManagerName($currentMgr);
                $submitted = $managers->isManagerSubmitted($_SESSION['empID'], "Rank");
                
                if(!class_exists('RankData')) {
                    include 'RankData.php';
                }
                $employees = new RankData;
                if(!isset($_SESSION['role'])){
                    $oneEmp = $employees->getAnEmployee($_SESSION['empID']);
                    $_SESSION['role'] = $oneEmp['Role'];
                }
                
                //Gather employee data and sort
                $empDisplay = array(array());
                $empResults = $employees->getEmployees($currentMgr, $currentGrade, $currentRole, $directs, 0, $empDisplay);
                
                if(isset($_REQUEST['h'])){$order = $employees->getSortOrder($sortBy);} else {$order = SORT_ASC;}
                
                $_SESSION['lastSort'] = $sortBy;
                $_SESSION['lastGrade'] = $currentGrade;
                $_SESSION['lastDirects'] = $directs;
                $_SESSION['lastOrder'] = $order;
                                
                $sortResults = $employees->sortEmployees($empResults, $sortBy, $order);
                
                //Display menus and table
                $numEmps = count($sortResults);
                $check = count($sortResults[0]);
                if(!$check){$numEmps = 0;}
                $employees->displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy, $order);

                //Get the Role of the selected manager so we display the proper header
                if($_SESSION['selectedManager'] == "000000"){
                    $_SESSION['role'] = "SD";
                } else {
                    $oneEmp = $employees->getAnEmployee($_SESSION['selectedManager']);
                    $_SESSION['role'] = $oneEmp['Role'];
                }
                $employees->displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order);
                $employees->displayEmployees($sortResults, $submitted);                
                
                if(!$submitted) {
                    //Display the form 
                    echo "<div style='float: left; width: 100%;'>";
                        echo "<form method='post' action='/UpdateSubmit.php?type=Rank'>";
                        echo "<input type='submit' name='button' value='Submit' onclick=\"return confirm('Are you sure you want to submit? Only your manager will be able to make future changes.');\">";
                        echo "</form>";
                    echo "</div>";
                }        
                unset($managers);
                unset($employees);
            } else{
                //Current user is not logged-in, go to MustSignInWeb
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
                header('Location: '.$uri.'/MustSignInWeb.php/');
                exit;
            }
            gc_disable();
        ?>
    </body>
</html>
