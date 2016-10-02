<?php
    // First we execute our common code to connect to the database and start the session
    include("../app/connect.php");

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']['role']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: ../index.php");

        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to ../index.php");
    }

    class ServerInfo {

        
        function Uptime() {

            $str   = @file_get_contents("/proc/uptime"); // Read the contents of the uptime file
            $num   = floatval($str); // Converts the string to a float

            $secs  = $num % 60; // Returns the remainder(modulo) of $num / 60
            $num   = (int)($num / 60);
            $mins  = $num % 60;
            $num   = (int)($num / 60);
            $hours = $num % 24;
            $num   = (int)($num / 24);
            $days  = $num;
    
            // The following returns an Associative Array which allows us to
            // assign a key to each time variable
            return array(
                "days" => $days,
                "hours" => $hours,
                "mins" => $mins,
                "secs" => $secs
            );
        }
    
        
        function Dbyte($Wert){
    
            if ($Wert >= 1099511627776) {
                $Wert = round($Wert / 1099511627776, 1) . " TB";
            } elseif ($Wert >= 1073741824) {
                $Wert = round($Wert / 1073741824, 1) . " GB";
            } elseif ($Wert >= 1048576) {
                $Wert = round($Wert / 1048576, 1) . " MB";
            } elseif ($Wert >= 1024) {
                $Wert = round($Wert / 1024, 1) . " kB";
            } else {
                $Wert = round($Wert, 0) . " Bytes";
            }
            return $Wert;
        }
        
        
        function Network($eth=false){
            if(!$eth){ $eth="wlan0";}
            $upload = @file_get_contents("/sys/class/net/$eth/statistics/rx_bytes");
            $download = @file_get_contents("/sys/class/net/$eth/statistics/tx_bytes");
            $networkTotal = floatval($upload) + floatval($download);
            return array(
                "upload_total" => $this->Dbyte($upload),
                "download_total" =>  $this->Dbyte($download),
                "network_total" => $this->Dbyte($networkTotal)
            );
        }
        
        
        function Cpu(){

            $cmd = "uname";
            $OS = strtolower(trim(shell_exec($cmd)));

            switch($OS){
                case('linux'):
                    $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
                    break;

                case('freebsd'):
                    $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
                    break;

                default:
                    unset($cmd);
            }

            if ($cmd != ''){
                $cpuCoreNo = intval(trim(shell_exec($cmd)));
            }

            $loads=sys_getloadavg();

            $load=$loads[0]/$cpuCoreNo;
            return $load;
        }
        
        
        
        function Memory(){
            $mem = file_get_contents("/proc/meminfo");
            if (preg_match('/MemTotal\:\s+(\d+) kB/', $mem, $matches))
            {
                $total = $matches[1];
            }
            unset($matches);
            if (preg_match('/MemFree\:\s+(\d+) kB/', $mem, $matches))
            {
                $free = $matches[1];
            }
            $free;
            $total;
            $usage = $total - $free;
            $precent = 100 * $usage / $total;
            return array(
                "total" => $this->Dbyte($total*1024),
                "free" => $this->Dbyte($free*1024),
                "usage" => $this->Dbyte($usage*1024),
                "precent" => round($precent,1),
            );
        }
        
        
        function Disk($disk=false){
            if(!$disk){ $disk='./';}
            $free=disk_free_space($disk);
            $total=disk_total_space($disk);
            $usage = $total - $free;
            $precent = 100 * $usage / $total;
            return array(
                "total" => $this->Dbyte($total),
                "free" => $this->Dbyte($free),
                "usage" => $this->Dbyte($usage),
                "precent" => round($precent,1),
            );
        }
        
        
        function Total($disk=false,$eth=false){
            $Network=$this->Network($eth);
            $Cpu=$this->Cpu();
            $Memory=$this->Memory();
            $Disk=$this->Disk($eth);
            return array(
                "network"=>$Network,
                "cpu"=>$Cpu,
                "memory"=>$Memory,
                "disk"=>$Disk,
            );
        }

    }






    $ServerInfo = new ServerInfo;
    $netInfo = $ServerInfo->Network();

    $q = intval($_GET['q']);

    switch ($q) {
        case "1":
            echo "$netInfo[upload_total]";
            break;
        case "2":
            echo "$netInfo[download_total]";
            break;
        case "3":
            echo "$netInfo[network_total]";
            break;

    }
