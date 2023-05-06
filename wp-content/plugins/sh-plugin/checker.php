<!DOCTYPE html>
<html lang="th" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no">
        <meta name="HandheldFriendly" content="true">
        <link rel="stylesheet" href="checker.css?v=<?=  time()?>">
                <!-- Include SweetAlert2 CSS file -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">

        <!-- Include SweetAlert2 JavaScript file -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    </head>
    <body>

    <?php
        define("GLO_URL","https://www.glo.or.th/api/checking/getcheckLotteryResult");

        function check_reward($data){
            // check if $data is a JSON object
            if (is_object($data)) {
                // check if the `statusType` key exists 
                if (isset($data->statusType) && isset($data->status_data)) {
                    // do something
                    if($data->statusType === 1) {
                        // ถูกรางวัล
                        return "ยินดีด้วยค่ะ คุณพี่ถูก ".$data->status_data[0]->reward;
                    }else{
                        // ถูกหวยกิน
                        return "เสียใจด้วยจ้าา คุณพี่ถูกหวยกิน";
                    }

                    return "ระบบกำลังดำเนินการ";
                }
            }
            return "ระบบกำลังดำเนินการ";
        }

        function bulk_check_lotto($day,$month,$year,$numbers){

            $date = $year.'-'.$month.'-'.$day;
            $data = array(
                'number' => array(),
                'period_date' => $date
            );
            
            if(is_array($numbers)){
                
                foreach($numbers as $number){
                    // Add Lottery Number to array
                    if(isset($number) && gettype($number) == 'string' && strlen($number) > 0){
                       //var_dump($number);
                       $payload = array(
                            "lottery_num" => $number,
                        );
                        $data['number'][] = $payload;
                    }
                    
                 }
            }
            
            //var_dump($data);
            // Convert the POST data to JSON format
            $jsonData = json_encode($data);

            // Set up cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, GLO_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Length: ' . strlen($jsonData),
                'Content-Type: application/json;charset=utf-8',
                'Origin: https://www.glo.or.th',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15',
                'Referer: https://www.glo.or.th/mission/reward-payment/check-reward'
            ));

            $response = curl_exec($ch);
            $err = curl_error($ch);

            curl_close($ch);

            if ($err) {
                echo "cURL Error #:" . $err;
            }

            return $response;

        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           
            $day = $_POST['datemonth'];
            $result_day = explode("-" , $day);
            $aday = $result_day[0];
            $month = $result_day[1];
            $year = $_POST['year'] - 543;
            $number = $_POST['lottery'];

            if(is_array($number)){
              
                    //print_r($number);
                    $response = bulk_check_lotto($aday, $month, $year, $number);

                    $result_data = json_decode($response);
                    $output = "";

                    // echo "<pre>";
                    // var_dump($result_data);
                    // echo "</pre>";

                    foreach ($result_data->response->result as $lotto_result){
                        //var_dump($lotto_result);
                        $re_msg = check_reward($lotto_result);
                        $output .= "งวดวันที่ : ".$lotto_result->date;
                        $output .= "<br>";
                        $output .= "เลขที่ตรวจ : ".$lotto_result->number;
                        $output .= "<br>";
                        $output .= "ผลการตรวจสอบ : ".$re_msg;
                        $output .= "<br>";
                        $output .= "===============<br>";
                    }

                    echo '<script>';
                    echo 'Swal.fire({
                        title: "ผลการตรวจสลากกินแบ่งรัฐบาล",
                        html: "<pre>' . $output . '</pre>",
                        confirmButtonText: "Confirm",
                        imageUrl: "lotto_logo.png",
                        imageWidth: 50,
                        imageHeight: 50,
                        imageAlt: "Custom image",
                        customClass: {
                            popup: "format-pre"
                        },
                        width: "50%",
                    });';
                    echo '</script>';
                
                
            }
        }
    ?>

        <div class="div1">ตรวจผลสลากกินแบ่งรัฐบาล</div>
        <div class="div2">ตรวจผลรางวัล จากหมายเลขสลากงวดประจำวันที่</div>
        
            <div class="flex-container">
                <form method="post" action="" style="display:flex">
        
                <label for="year" class="label-year">พ.ศ.</label>
                <select id="year" name="year" class="year-select" onchange="work_flow()" required>
                    <option value="">--โปรดเลือก--</option>
                    <option value="2548">2548</option>
                    <option value="2549">2549</option>
                    <option value="2550">2550</option>
                    <option value="2551">2551</option>
                    <option value="2552">2552</option>
                    <option value="2553">2553</option>
                    <option value="2554">2554</option>
                    <option value="2555">2555</option>
                    <option value="2556">2556</option>
                    <option value="2557">2557</option>
                    <option value="2558">2558</option>
                    <option value="2559">2559</option>
                    <option value="2560">2560</option>
                    <option value="2561">2561</option>
                    <option value="2562">2562</option>
                    <option value="2563">2563</option>
                    <option value="2564">2564</option>
                    <option value="2565">2565</option>
                    <option value="2566">2566</option>
                </select>

                <label for="datemonth" class="label-month">วัน-เดือน</label>
                <select id="datemonth" name="datemonth" class="month-select" required>
                    <?php 
                                                echo "<option value='' selected>--โปรดเลือก--</option>";
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
            
            </div>

        <div class="lottery-forms">
            
                <div class= "lotto_formss">
                
                    <lottery>เลขสลาก 1</lottery>
                    <div class="lottery-input">
                        <input type="text" name="lottery[]" id="lottery-input" placeholder="กรอกเลขสลาก 6 หลัก" pattern="[0-9]{6}" required>
                    </div>
               
                    <lottery>เลขสลาก 2</lottery>
                    <div class="lottery-input">
                        <input type="text" name="lottery[]" id="lottery-input" placeholder="กรอกเลขสลาก 6 หลัก" pattern="[0-9]{6}">
                    </div>
                
                    <lottery>เลขสลาก 3</lottery>
                    <div class="lottery-input">
                        <input type="text" name="lottery[]" id="lottery-input" placeholder="กรอกเลขสลาก 6 หลัก" pattern="[0-9]{6}">
                    </div>
                    </div>
                </div>
                
                <button type="submit" name="submit" value="submit" style="margin:0 auto;">
                    ตรวจรางวัล
                </button>
                </form>
        </div>
</body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
