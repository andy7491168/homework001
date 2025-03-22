<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>chart</title>
    </head>
    <body>
    <div>
        <canvas id="myChart"></canvas>


    </div>
    </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

//   const config = {
//     type: 'bar',
//     data: {
//       labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
//       datasets: [{
//         label: '# of Votes',
//         data: [12, 19, 3, 5, 2, 3],
//         borderWidth: 1
//       }]
//     },
//     options: {
//       scales: {
//         y: {
//           beginAtZero: true
//         }
//       }
//     }
//   };



  const config = {
    type: 'line',
    data: {
        @php
           $nameList = '';
            foreach ($data as $row){
                if($nameList!=''){
                    $nameList .= ",'".$row['name']."'";
                }else{
                    $nameList .= "'".$row['name']."'";
                }   
            }
           $nameList = "labels: [". $nameList. "],";
           echo $nameList 
        @endphp
        datasets: [
            {
                label: 'Booked',
                @php
                    $bookedList = '';
                    foreach ($data as $row){
                        if($bookedList!=''){
                            $bookedList .= ",".$row['booked_amount'];
                        }else{
                            $bookedList .= $row['booked_amount'];
                        }   
                    }
                    $bookedList = "data: [". $bookedList. "],";
                    echo $bookedList 
                @endphp
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 3
            },
            {
                label: 'Actual',
                @php
                    $actualList = '';
                    foreach ($data as $row){
                        if($actualList!=''){
                            $actualList .= ",".$row['actual_amount'];
                        }else{
                            $actualList .= $row['actual_amount'];
                        }   
                    }
                    $actualList = "data: [". $actualList. "],";
                    echo $actualList 
                @endphp
                
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                borderColor: 'rgba(0, 0, 255, 1)',
                borderWidth: 3
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

  new Chart(ctx, config);
</script>
