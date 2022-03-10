<?php
//importing required functions
require_once  'SqlFunctions.php';
$USER_FUN = new SqlFunctions();

// Function responsible to return the correspondant page number
function pagi_get($page_key, $last_page){

    $page_number = 1;
    $USER_FUN = new SqlFunctions();
    //When its a GET method
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //Check that a page exists
        if(isset($_GET[$page_key]) && is_numeric($_GET[$page_key]) && !empty(trim($_GET[$page_key]))){
            $page_number = $USER_FUN->valUSER_IDation($_GET[$page_key]);
            if($page_number <= 0){
                $page_number = 1;
            }
            elseif($page_number > $last_page){
                $page_number = $last_page;
            }
        }
        else{
            $page_number = 1;
        }
    }
    else{
        $page_number = 1;
    }
    return $page_number;

}

function pagi_buttons($buttons){

    if(is_numeric($buttons)){
        if($buttons >= 3){
            if($buttons%2){
                return $buttons; //Odd
            }
            else{
                return false;  //Even
            }
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }

}
// function that gets all the records within a limit and offset
function get_records($offset, $limit){

    $USER_FUN = new SqlFunctions();

    $tbl_structure .= <<<END
        <div class="container">
        <div class="ins-box ins-box-set">
            <table class="table table-hover">
                <thead class="bg-primary" style="color: white !important;">
                    <tr>
                        <th scope="col">USER_ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">FirstName</th>
                        <th scope="col">LastName</th>
                        <th scope="col">Gender</th>
                    </tr>
                </thead>
                <tbody>
    END;
    //fetching from DB from user_Details table
    $fetch_rec = $USER_FUN->show_rec('user_details', $offset, $limit);
    // Success response
    if($fetch_rec){
        // Looping on the data received and storing them in table_structre
        foreach($fetch_rec as $rec_data){
            $tbl_structure .= '<tr ><th scope="row">'.$rec_data['USER_ID'].'</th><td scope="row">'.$rec_data['Username'].'</td><td scope="row">'.$rec_data['FirstName'].'</td><td scope="row">'.$rec_data['LastName'].'</td><td scope="row">'.$rec_data['Gender'].'</td></tr>';
        }
    }//Empty return
    else{
        $tbl_structure .= '<tr><td colspan="5"><h3>Record not Found</h3></td></tr>';
    }

    $tbl_structure .= '</tbody></table></div></div>';

    echo $tbl_structure;

}
// main function
function pagination(){

    $USER_FUN = new SqlFunctions();

    $total_buttons = pagi_buttons('7');
    $per_page_records = 15;
    $total_records = $USER_FUN->rec_count('user_details');
    $last_page = ceil($total_records/$per_page_records);
    $page_number = pagi_get('page', $last_page);
    $half = floor($total_buttons/2);

    $show_page_info = '<div class="container"><div class="ins-box"><h5>Showing Result '.$page_number.' / '.$last_page.' </h5></div></div>';

    echo $show_page_info;

    get_records(($page_number * $per_page_records - $per_page_records), $per_page_records);

    $pagination .= '<div class="container"><nav><ul >';

    if($page_number < $total_buttons && ($last_page == $total_buttons || $last_page > $total_buttons)){

        if($page_number >= 2){
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page=1">&lt;&lt;</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number - 1).'">Previous</a></li>';
        }

        if($page_number >= ($half + 2)){

            for($j=($page_number-$half); $j<=($page_number+$half); $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }
        }
        else{
            for($j=1; $j<=$total_buttons; $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active "><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }
        }

        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number + 1).'">Next</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$last_page.'">&gt;&gt;</a></li>';
    }
    elseif($page_number >= $total_buttons && $last_page > $total_buttons){

        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page=1">&lt;&lt;</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number - 1).'">Previous</a></li>';

        if(($page_number+$half) >= $last_page){

            for($j=($last_page-$total_buttons+1); $j<=$last_page; $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }

        }
        elseif(($page_number+$half) < $last_page){

            for($j=($page_number-$half); $j<=($page_number+$half); $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }
        }

        if($page_number != $last_page){
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number + 1).'">Next</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$last_page.'">&gt;&gt;</a></li>';
        }

    }

    $pagination .= '</ul></nav></div>';

    echo $pagination;

}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta Username="viewport" content="wUSER_IDth=device-wUSER_IDth, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagination Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="Stylesheet/stylesheet.css">
</head>

<body>

    <div class="container-fluUSER_ID">

        <div class="container">
            <ul class="nav justify-content-center bg-primary">
                <li class="nav-item">
                    <div class="nav-link heading">PHP Pagination</div>
                </li>
            </ul>
        </div>

        <?php

            pagination();

        ?>

    </div>
</body>

</html>
