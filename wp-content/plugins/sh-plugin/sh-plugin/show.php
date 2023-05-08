<!DOCTYPE html>
<html lang="th">
    <head>
            <?php 
                    set_time_limit(300);
                    //$xml = simplexml_load_file('https://www.lottery.co.th/feed');
                    //$value = (string) $xml->channel->item[0]->title;
            ?>

        <?php
            function select_for_show($yearNow) {

                $curl = curl_init();
            
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://thai-lottery1.p.rapidapi.com/gdpy?year=$yearNow",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-RapidAPI-Host: thai-lottery1.p.rapidapi.com",
                        "X-RapidAPI-Key: be72945233msha7f60a56f8df87ep18f7bcjsn366b4116954e",
                        "content-type: application/octet-stream"
                    ],
                ]);
            
                $response = curl_exec($curl);
                $err = curl_error($curl);
            
                curl_close($curl);
            
                if ($err) {
                    return "cURL Error #:" . $err;
                } else {
                    $data = json_decode($response, true); // แปลง JSON เป็น associative array
                    if (is_array($data)) {
                        // ถ้า $data เป็น array ให้ทำการ loop และสร้าง HTML ตาราง
                        return $data;
                    } else {
                        // ถ้า $data ไม่ใช่ array ให้ส่ง error message กลับไปแสดงผล
                        return "Error: Cannot decode JSON response from API.";
                    }
                }
                return $data;
            }
            
            function list_price_win($year, $month, $day){
                    //$url = 'https://thai-lottery1.p.rapidapi.com/?date=16052564';

                    $payload = sprintf("%02d%02d%4d",$day,$month,$year);
                    //echo $payload;
                    $url = 'https://thai-lottery1.p.rapidapi.com/?date='.$payload;
                    $options = array(
                        'http' => array(
                            'header' => "X-RapidAPI-Key: be72945233msha7f60a56f8df87ep18f7bcjsn366b4116954e\r\n" .
                                        "X-RapidAPI-Host: thai-lottery1.p.rapidapi.com\r\n"
                        )
                    );
                    $context = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                    $data = json_decode($response, true);

                    //print_r($data);
                    return $data;
            }

           
        ?>
    </head>
    <style>
        .myDiv {
            border: 2px outset #3b3b3b;
            background: rgb(0,12,78);
            background: radial-gradient(circle, rgba(0,12,78,1) 0%, rgba(1,0,34,1) 100%);
            color: white;
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .myDivH {
            background: rgb(9,9,121);
            background: linear-gradient(0deg, rgba(9,9,121,1) 0%, rgba(0,43,137,1) 49%, rgba(9,9,121,1) 100%);
            padding: 10px;
        }

        .myBox {
            font-size: 24px;
            color: white;
            background: rgb(0,39,255);
            background: radial-gradient(circle, rgba(0,39,255,1) 0%, rgba(1,0,34,1) 100%); 
            padding-top: 25px;
            padding-bottom: 25px;     
        }

        .btnsh{
            color: white;
            background: rgb(19,56,134);
            background: linear-gradient(14deg, rgba(19,56,134,1) 0%, rgba(7,65,189,1) 52%, rgba(3,3,73,1) 94%);
        }

        .homesh{
            background: rgb(224,224,224);
            background: linear-gradient(247deg, rgba(224,224,224,1) 5%, rgba(255,255,255,1) 31%, rgba(255,255,255,1) 69%, rgba(226,226,226,1) 99%);
        }

        .md-text{
            font-size: 18px;
        }

        .sm-text{
            font-size: 12px;
        }
    </style>
    <body class="homesh blog  wide">
        <div id="page" class="hfeed site">
            <div id="main" class="clearfix">
                <?php
                    date_default_timezone_set('asia/bangkok');                  
                    $year = date('Y');                                  // 2023
                    $yearNow = intval($year)+543;                       //get yearNow yearThai
                    $responseYear = array();
                    $responseYears = array();
                    //print_r();
                    
                ?>
                    <form method="post" action="">
                        <table class="table table-hover text-nowrap" id="attribute_table">
                            <h3 class="widget-title">
                                <thead>
                                    <tr>
                                        <th>ปี</th>
                                        <th>วัน / เดือน</th>
                                    </tr>
                                </thead>

                                <tr id="row1">
                                    <td>
                                        <select id="year" name="year" class="form-control-year" onchange="work_flow()" required>
                                            <?php
                                                echo "<option value='' selected>ปี</option>";
                                                // Generate options for year dropdown
                                                for ($i = $yearNow; $i > 2547; $i--) {  
                                                                           //หาค่าปีแล้วดึงมาใส่ซะ
                                                    if($i == $yearNow){
                                                        //$j = $i - 543;
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                    else if($i != intval($yearNow)){
                                                        //$j = $i - 543;
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <?php
                                        $monthTh = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                                                    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
                                    ?>
                                    <td>
                                        <select name="datemonth" id="datemonth" class="form-control-day-value" required>
                                            <?php 
                                                echo "<option value='' selected>วัน / เดือน</option>";
                                                for($i=$yearNow;$i>2548;$i--){  
                                                    $responseYear[$i] = select_for_show($i);   
                                                                                              // value year
                                                    for($j=count($responseYear[$i])-1;$j>=0;$j--){   
                                                        $rpDay[$i][$j] = substr($responseYear[$i][$j],0,2); 
                                                        $rpADay = $rpDay[$i][$j];
                                                        $rpMonth[$i][$j] = substr($responseYear[$i][$j],2,2);
                                                        $rpMonthInt = intval( $rpMonth[$i][$j]);
                                                        $idxMonth = $rpMonthInt -1;
                                                        //$datas[$i][$j] = list_price_win($i, $rpMonth[$i][$j], $rpDay[$i][$j]);
                                                        //if($rpMonthInt==)
                                                        
                                                            if(($i==$yearNow) && ($j==count($responseYear[$i])-1)){
                                                                echo "<option data-parent='$i' value='$rpADay,$rpMonthInt'>
                                                                $rpADay".' / '."$monthTh[$idxMonth]
                                                            </option>"; ?><br><?php
                                                            }
                                                            // else{
                                                            //     echo "<option data-parent='$i' value='$rpADay,$rpMonthInt'>
                                                            //     $rpADay".' / '."$monthTh[$idxMonth]
                                                            // </option>"; ?><br><?php
                                                            // }
                                                          
                                                    }    
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <button type="submit" name="check_lotto_btn" value="submit" title="ดูเลขถูกรางวัล" class="btnsh">ดูเลขถูกรางวัล</button>
                                        </div>
                                    </td>
                                </tr>
                                </form>
                            </h3>
                            
                                    <div class="textwidget custom-html-widget">
                                        <?php
                                            //$datas = list_price_win($year)
                                           
                                            //var_dump( $datas[$yearNow][$lastLotto] );
                                            //echo $dataNow[0][1];
                                            //print_r($dataNow);

                                            $lastLotto = count($responseYear[$yearNow])-1;
                                            $datas[$yearNow][$lastLotto] = list_price_win($yearNow, $rpMonth[$yearNow][$lastLotto], $rpDay[$yearNow][$lastLotto]);
                                            $dataNow = $datas[$yearNow][$lastLotto];
                                            //print_r($dataNow);
                                            //echo $rpDay[$yearNow][$lastLotto];
                                            //echo $rpMonth[$yearNow][$lastLotto];

                                            // if(isset($_POST['check_lotto_btn'])){
                                                
                                            //        // echo "Event submit;";
                                            //         $daymonth = explode(",", $_POST['day']);
                                            //         $day = intval($daymonth[0]);
                                            //         $month = intval($daymonth[1]);
                                            //         $year = $_POST['year'];
                                            //         //DEbug
                                            //         echo $day."-".$month."-".$year;   
                                            //         $dataNow = list_price_win($year,$month,$day);  
                                            // }
                                           
                                        ?>
                                        <div class="entry-content">
                                            <div class="table-responsive">
                                                <table id="reward1" class="easy-table easy-table-default table">
                                                    <caption>
                                                        <?php
                                                            if(isset($_POST['check_lotto_btn'])){
                                                
                                                                // echo "Event submit;";
                                                                 $days = $_POST['datemonth'];
                                                                 //$daymonth = explode(",", $_POST['day']);
                                                                 $daymonth = explode("-", $days);
                                                                 //print_r($daymonth);
                                                                 $day = intval($daymonth[0]);
                                                                 $month = intval($daymonth[1]);
                                                                 $AmonthTh = $monthTh[$month-1];
                                                                 $year = $_POST['year'];
                                                                 //DEbug
                                                                 ?><td class="myBox"><?php
                                                                 echo 'งวด '.$day." ".$AmonthTh." ".$year;   
                                                                 $dataNow = list_price_win($year,$month,$day); 
                                                                 ?></td><?php 
                                                            }else{
                                                                $daymonth[0] = $rpDay[$yearNow][$lastLotto];
                                                                $daymonth[1] = $rpMonth[$yearNow][$lastLotto];
                                                                //$daymonth = explode(",", $_POST['day']);
                                                                //$daymonth = explode("-", $days);
                                                                //print_r($daymonth);
                                                                $day = intval($daymonth[0]);
                                                                $month = intval($daymonth[1]);
                                                                $AmonthTh = $monthTh[$month-1];
                                                                $year = $yearNow;
                                                                //DEbug
                                                                ?><td class="myBox"><?php
                                                                echo 'งวด '.$day." ".$AmonthTh." ".$year;   
                                                                //$dataNow = list_price_win($year,$month,$day); 
                                                                ?></td><?php 
                                                            }
                                                        ?>
                                                    </caption>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="reward1" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">รางวัลที่ 1</div><br/><div class="sm-text">มี 1 รางวัลๆละ 6,000,000 บาท</div>
                                                    </caption>
                                                    <tbody id="info">
                                                        <tr>
                                                            <td class="myDiv"><?php echo $dataNow[0][1]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="nearreward1" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">รางวัลข้างเคียงรางวัลที่ 1</div><br/><div class="sm-text">มี 2 รางวัลๆละ 100,000 บาท</div>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td class="myDiv"><?php echo $dataNow[0][1]-'1'; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[0][1]+'1'; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="reward2" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">ผลสลาก รางวัลที่ 2</div><br/><div class="sm-text">มี 5 รางวัลๆละ 200,000 บาท</div>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td class="myDiv"><?php echo $dataNow[5][1]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[5][2]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[5][3]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[5][4]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[5][5]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="reward3" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">ผลสลาก รางวัลที่ 3</div><br/><div class="sm-text">มี 10 รางวัลๆละ 80,000 บาท</div>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td class="myDiv"><?php echo $dataNow[6][1]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][2]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][3]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][4]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][5]; ?></td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="myDiv"><?php echo $dataNow[6][6]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][7]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][8]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][9]; ?></td>
                                                            <td class="myDiv"><?php echo $dataNow[6][10]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="reward4" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">ผลสลาก รางวัลที่ 4</div><br/><div class="sm-text">มี 50 รางวัลๆละ 40,000 บาท</div>
                                                    </caption>
                                                    <tbody>
                                                        <?php
                                                            for($i=0;$i<50;$i=$i+10){
                                                        ?>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[7][1+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][2+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][3+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][4+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][5+$i]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[7][6+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][7+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][8+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][9+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[7][10+$i]; ?></td>
                                                                    </tr>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="reward5" class="easy-table easy-table-default table">
                                                    <caption class="myDivH">
                                                        <div class="md-text">ผลสลาก รางวัลที่ 5</div><br/><div class="sm-text">มี 100 รางวัลๆละ 20,000 บาท</div>
                                                    </caption>
                                                    <tbody>
                                                        <?php
                                                            for($i=0;$i<100;$i=$i+20){
                                                        ?>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[8][1+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][2+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][3+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][4+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][5+$i]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[8][6+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][7+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][8+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][9+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][10+$i]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[8][11+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][12+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][13+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][14+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][15+$i]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="myDiv"><?php echo $dataNow[8][16+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][17+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][18+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][19+$i]; ?></td>
                                                                        <td class="myDiv"><?php echo $dataNow[8][20+$i]; ?></td>
                                                                    </tr>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                     
                        
            </div>
        </div>
    </body>    
        <?php
            //select_for_show();
           // echo count($responseYears);
        ?>
        
        <link rel="stylesheet" href="https://www.lottery.co.th/style.min2020.css" type="text/css"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script>
            $('#year').bind('change', function () {
                var parent = $(this).val();
                console.log(parent)
                $('#day').children().each(function () {
                    if ($(this).data('parent') != parent) {
                        $(this).hide();
                    } else
                        $(this).show();
                });
            });
        </script>

        <script>
            async function get_datemonth(){
                let year = document.getElementById("year").value;
                console.log(year);
                const url = 'https://thai-lottery1.p.rapidapi.com/gdpy?year='+year;
                const options = {
                    method: 'GET',
                    headers: {
                        'X-RapidAPI-Key': 'be72945233msha7f60a56f8df87ep18f7bcjsn366b4116954e',
                        'X-RapidAPI-Host': 'thai-lottery1.p.rapidapi.com'
                    }
                };

                try {
                    const response = await fetch(url, options);
                    const date_months = await response.json();
                    //console.log(date_months);
                    return date_months
                } catch (error) {
                    console.error(error);
                }
            }

            function add_option_element(date){
                /*
                *   date : parameter format `16042566`
                */
                // console.log("add_option_element")
                // console.log(date);
                const monthTh = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];

                let selectElement = document.getElementById("datemonth");
                const newOption = document.createElement("option"); // Create a new option element
                let day = date.substr(0,2)
                let text_month = monthTh[parseInt(date.substr(2,2))-1]
                newOption.value = day + "-"+date.substr(2,2); // Set the value of the new option
                newOption.text = day + "-"+text_month; // Set the text of the new option
                selectElement.add(newOption); // Append the new option to the select element's options collection
            }

            function work_flow(){
                // Clear Datemonth options
                document.getElementById("datemonth").options.length = 0

                get_datemonth().then( date_months => {
                    //console.log("Result ")
                    //console.log(date_months);
                    date_months.forEach( date => {
                        add_option_element(date);
                    })
                })
            }
        </script>
    
</html>
<!-- This website is like a Rocket, isn't it? Performance optimized by WP Rocket. Learn more: https://wp-rocket.me - Debug: cached@1681173548 -->
