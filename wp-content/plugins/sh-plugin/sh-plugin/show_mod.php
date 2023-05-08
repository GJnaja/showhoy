
    <?php
        function thai_lotto($year) {

            $curl = curl_init();
        
            curl_setopt_array($curl, [
                //CURLOPT_URL => "https://thai-lottery1.p.rapidapi.com/gdpy?year=2564",
                CURLOPT_URL => "https://thai-lottery1.p.rapidapi.com/gdpy?year=$year",
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
                    $html = '<table><thead><tr><th>วันที่</th><th>รางวัลที่ 1</th><th>รางวัลเลขท้าย 2 ตัว</th></tr></thead><tbody>';
                    //echo $html;
                     var_dump($data);
                //    foreach($data as $result){
                //         echo $result."</br>";
                //    }
                // foreach ($data as $result) {
                //     $date = date('d/m/Y', strtotime($result['date']));
                //     $first_prize = implode(', ', $result['first_prize']);
                //     $last_two_digits = implode(', ', $result['last_two_digits']);
                //     $html .= "<tr><td>$date</td><td>$first_prize</td><td>$last_two_digits</td></tr>";
                // }
                //$html .= '</tbody></table>';
                //return $html;
                return $data;
                } else {
                    // ถ้า $data ไม่ใช่ array ให้ส่ง error message กลับไปแสดงผล
                    return "Error: Cannot decode JSON response from API.";
                }

                $html = '<table><thead><tr><th>วันที่</th><th>รางวัลที่ 1</th><th>รางวัลเลขท้าย 2 ตัว</th></tr></thead><tbody>';
                // var_dump($data);
                //    foreach($data as $result){
                //         echo $result."</br>";
                //    }
                // foreach ($data as $result) {
                //     $date = date('d/m/Y', strtotime($result['date']));
                //     $first_prize = implode(', ', $result['first_prize']);
                //     $last_two_digits = implode(', ', $result['last_two_digits']);
                //     $html .= "<tr><td>$date</td><td>$first_prize</td><td>$last_two_digits</td></tr>";
                // }
                // $html .= '</tbody></table>';
                // return $html;
            }
            return $data;
        }
      
        thai_lotto("2566");