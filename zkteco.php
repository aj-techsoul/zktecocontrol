<?php  
CLASS zktecocontrol
{

    // private $pythonPath = 'C:\Users\USER\AppData\Local\Programs\Python\Python311-32\python.exe';
    private $ip = "192.168.1.201";
    private $port = "4370";
    private $pythonPath;

    public function __construct()
    {
        // Determine the Python executable path dynamically
        $this->pythonPath = $this->getPythonPath();
    }


    private function getPythonPath()
    {
        // Run the 'where' command to find the Python executable path
        $pythonPath = shell_exec('where python');

        // Extract the first line (the path)
        $pythonPath2 = trim(explode("\n", $pythonPath)[0]);
        // echo $pythonPath2;
        return $pythonPath2;
    }


    public function pyrun($file="test.py"){
        // python test.py
        $scriptPath = "pyacc\\$file";

        // Use escapeshellarg to escape any special characters in the script path
        $scriptPathEscaped = escapeshellarg($scriptPath);

        // Construct the command
        $command = $this->pythonPath." $scriptPathEscaped 2>&1";

        // Run the command and capture the output
        $result = shell_exec($command);

        // Decode the result as JSON
        $data = json_decode($result, true);

        // Check if decoding was successful
        if (json_last_error() == JSON_ERROR_NONE) {
            // Output as JSON
            header('Content-Type: application/json');
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            // Output as plain text
            return $result;
        }

    }



// Function to check if the result has a table-like structure
private function hasTableStructure($result) {
    // Check if the result contains lines with at least two columns
    return preg_match('/\|.*\|/', $result);
}

// Function to process the tabular result and convert it to an array
private function processTableResult($result) {
    $c = explode("\n",$result);
if(count($c) > 2){
    $headers = explode("|",$c[1]);
    if(count($headers) > 0){
    unset($headers[0]);
    unset($headers[count($headers)]);
    }
    $j = 0;
    for($i=2; $i < count($c); $i++){
        $row = explode("|",$c[$i]);
        if(count($row) > 0){
            unset($row[0]);
            unset($row[count($row)]);
        }
        if(count($row) > 0){
            $x = 1;
            foreach($row as $r){
                $h = trim($headers[$x]);
                $data[$j][$h] = trim($r); 
                $x++;
            }
        }
        $j++;
    }    
    
}
return $data;
}






public function run($command) {
    // Construct the Python script path
    $scriptPath = $command;

    // Use escapeshellarg to escape any special characters in the script path
    $scriptPathEscaped = escapeshellarg($scriptPath);

    // Construct the full command
    $fullCommand = "{$scriptPath} 2>&1";

    // echo $fullCommand;

    $result = shell_exec($fullCommand);

    // check for command executed or not
    if (strpos($result, "Traceback") === 0) {
        return "Try Again!";
    }

 // return $result;
    // Check if the result has a table-like structure
    if ($this->hasTableStructure($result)) {
        // Process the result and convert it to JSON
        $data = $this->processTableResult($result);

        // Output the data as JSON
        header('Content-Type: application/json');
        return json_encode($data, JSON_PRETTY_PRINT);
    } else {
        // Output the raw text
        return $result;
    }
}


public function pyzaccess($command){
    $ipz = $this->ip;
    $result = self::run("pyzkaccess connect $ipz  $command");
    return $result;
}

public function pyzcontrol($case){
    switch($case){
        case "opendoor":
           $command = " relays switch_on";
           $ret = self::pyzaccess($command);
           return (strlen($ret) > 0) ?  $ret : 'true';
        break;
        default : 
           $command = "table User";
           $ret = self::pyzaccess($command);
           return $ret;
        break;
    }
}


public function pyzuser($tbl,$formdata="",$where=""){
    if(strlen($where) > 0){
        // where
        $ipz = $this->ip;
        $result = self::run("pyzkaccess connect $ipz $tbl $where");
        return $result;    
    }
    else
    {

// Replace this with your actual JSON data
// $jsonData = '{"card": "14322222", "group": "1", "pin": "102", "password": "1234", "super_authorize": true, "start_time": 1645257600, "end_time": 1645344000}';
$jsonData = json_encode($formdata);

// Command to run the Python script
$command = 'python pyacc/adduser.py';

// Open a process with proc_open
$process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

// Check if the process is opened successfully
if (is_resource($process)) {
    // Write JSON data to the standard input
    fwrite($pipes[0], $jsonData);
    fclose($pipes[0]);

    // Read the output from the standard output
    $result = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // Read errors from the standard error
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    // Close the process
    $returnValue = proc_close($process);

    // Output the result or errors
    if ($returnValue === 0) {
        // Output as JSON
        header('Content-Type: application/json');
        echo json_encode(json_decode($result, true), JSON_PRETTY_PRINT);
    } else {
        // Output errors
        echo $errors;
    }
} else {
    echo 'Failed to open process.';
}




        // // New
        // $ipz = $this->ip;
        // $result = self::run("pyzkaccess connect $ipz $tbl $where");
        // return $result;    
    }
}



public function pycmd($command, $jsonData = null) {
    // Open a process with proc_open
    $process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

    // Check if the process is opened successfully
    if (is_resource($process)) {
        // Write JSON data to the standard input if provided
        if ($jsonData !== null) {
            fwrite($pipes[0], $jsonData);
            fclose($pipes[0]);
        }

        // Read the output from the standard output
        $result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read errors from the standard error
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // Close the process
        $returnValue = proc_close($process);



    // check for command executed or not
    if (strpos($returnValue, "Traceback") === 0) {
        return "Try Again!";
    }

        // Output the result or errors
        if ($returnValue === 0) {

            // Output as JSON
             if(strlen($ret) == 0){
                $ret = "true";
                return $ret;
            }
            else
            {
            header('Content-Type: application/json');
            $ret = json_encode(json_decode($result, true), JSON_PRETTY_PRINT);
            return $ret;
            }
        } else {
            // Output errors
            $ret = $errors;
            return $ret;
        }
    } else {
        $ret = 'Failed to open process.';
        return $ret;
    }
}




}






// echo "<pre>";
// $zk = new zktecocontrol;
// echo $zk->run("User",true);
// echo $zk->run('pyzkaccess connect 192.168.1.201 table User');
// echo $zk->run("--version");
// echo $zk->pyrun("test_add.py");
// echo $zk->pyrun("test.py");
// echo $zk->run('pyzkaccess connect 192.168.1.201 relays switch_on');

// $ret = $zk->run('pyzkaccess connect 192.168.1.201 relays switch_on');
// var_dump($ret);
// echo $zk->pyzcontrol('opendoor');


// echo $zk->pyzuser("User");
// echo $zk->pycmd('python pyacc/adduser.py', '{"card": "14322222", "group": "1", "pin": "103", "password": "1234", "super_authorize": true, "start_time": '.strtotime("2024-02-21 00:00").', "end_time": '.strtotime("2024-02-21 23:59").'}');

// echo $zk->pycmd('python pyacc/deluser.py', '{"card": "14322222"}');
?>