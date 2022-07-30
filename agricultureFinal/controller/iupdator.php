<?php
    session_start();

    if(isset($_COOKIE['astatus']) && isset($_SESSION['id']) && isset($_SESSION['pass']))
    { 

        $required = array('changeID','heading','toChange');
        $id_found = false;
        // Loop over field names, make sure each one exists and is not empty
        $error = false;
        foreach($required as $field) 
        {
            if (empty($_POST[$field])) 
            {
                $error = true;
                break;
            }
        }

        if(!$error)
        {
            $id = $_REQUEST['changeID'];
            $heading = $_REQUEST['heading'];
            $new = $_REQUEST['toChange'];
            $a = array();
            
            $file = fopen('../model/inventoryList.txt','r');
            while(!feof($file))
            {
                $data = fgets($file);
                $user = explode("|",$data);

                if($user[0] == $id)
                {
                    $id_found = true;
                    switch($heading)
                    {
                        case "Name":
                            $newdata = str_replace($user[1],$new,$data);
                            array_push($a,$newdata);
                            break;
                        case "Price":
                            $newdata = str_replace($user[2],$new,$data);
                            array_push($a,$newdata);
                            break;
                        case "Status":
                            $newdata = str_replace($user[3],$new,$data);
                            array_push($a,$newdata);
                            break;
                        case "Stock":
                            $newdata = str_replace($user[4],$new,$data)."\r\n";
                            array_push($a,$newdata);
                            break;
                                            
                    }
                    
                }
                else
                {
                    array_push($a,$data);

                }
            }
            fclose($file);

            if(!$id_found)
            {
                echo "Crop does not exist";
                echo "<br><a href='../view/iupdate.php'>Back</a>";
                echo "<br><a href='../view/ahome.php'>Go Home</a>";
            }

            else
            {
                //print_r($a);
                $write = fopen('../model/inventoryList.txt','w');
                //fwrite($write);
                $updated = '';
                foreach($a as $item)
                {
                    $updated = $updated.$item;
                }
                echo $updated;
                fwrite($write,$updated);
                fclose($write);
                //$_SESSION['pass'] = $npass;
                header('location: ../view/inventory.php');
            }
        }
        else
        {
            echo "Please enter all details properly";
            echo "<br><a href='../view/iupdate.php'>Back</a>";
            echo "<br><a href='../view/ahome.php'>Go Home</a>";
        }
    }
    else
    {
        echo "Invalid request";
        echo "<br><a href='../view/login.php'>Login</a>";
    }    
?>