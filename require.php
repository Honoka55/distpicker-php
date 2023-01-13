<?php
define("HOST", "localhost");
define("USER", "root");
define("PSWD", "your_password");
define("DBNM", "your_database");
$link = @(mysqli_connect(HOST, USER, PSWD, DBNM, 3316)) or die("数据库连接失败！");

function printjs($link){
    echo '<script>';
    $divs = mysqli_query($link, "SELECT * FROM divcode;");
    $divn = mysqli_num_rows($divs);
    echo '
        var divcode = {';
    for ($i = 0; $i < $divn; $i++) {
        $divr = mysqli_fetch_assoc($divs);
        if ($divr['code'] % 100 == 0) {
            echo $divr['code'] . ': {}, ';
        }
    }
    echo '};';
    $divs = mysqli_query($link, "SELECT * FROM divcode;");
    for ($i = 0; $i < $divn; $i++) {
        $divr = mysqli_fetch_assoc($divs);
        if ($divr['code'] % 100 != 0) {
            $up = floor($divr['code'] / 100) * 100;
            echo " divcode[$up][" . $divr['code'] . "] = '" . $divr['name'] . "';";
        } elseif ($divr['code'] % 10000 != 0) {
            $up = floor($divr['code'] / 10000) * 10000;
            echo " divcode[$up][" . $divr['code'] . "] = '" . $divr['name'] . "';";
        }
    }
    echo <<<EOF

        function provchange() {
            var prefselect = document.getElementById("pref-select");
            prefselect.options.length = 1;
            var newpro = document.getElementById("prov-select").value;
            for (var i in divcode[newpro]) {
                prefselect.add(new Option(divcode[newpro][i], i));
            }
            prefchange();
        }
        function prefchange() {
            var counselect = document.getElementById("coun-select");
            counselect.options.length = 1;
            var newpro = document.getElementById("pref-select").value;
            for (var i in divcode[newpro]) {
                counselect.add(new Option(divcode[newpro][i], i));
            }
        }
        function setcode(code) {
            document.getElementById("prov-select").value = Math.floor(code / 10000) * 10000;
            provchange();
            document.getElementById("pref-select").value = Math.floor(code / 100) * 100;
            prefchange();
            document.getElementById("coun-select").value = code;
        }
        function confirm(other = 1){
            if(other && document.getElementById('coun-select').value != 0){
                document.getElementById("addrbox").classList.remove("mdui-textfield-invalid");
                document.getElementById('update').disabled=false;
            } else {
                document.getElementById("addrbox").classList.add("mdui-textfield-invalid");
                document.getElementById('update').disabled=true;
            }
        }
    </script>

EOF;
}

function printselects($link, $value = 000000, $other = 1){
    // printjs($link);
    echo <<<EOF
<select class="mdui-select mdui-p-x-2" id="prov-select" onchange="provchange();confirm($other);">
    <option value="0" selected>省区</option>
EOF;
    require_once "include.php";
    $prov = mysqli_query($link, "SELECT * FROM divcode WHERE MOD(code, 10000) = 0;");
    for ($i = 0; $i < 31; $i++) {
        $provr = mysqli_fetch_assoc($prov);
        echo "<option value='" . $provr['code'] . "'>" . $provr['name'] . "</option>";
    }
    echo <<<EOF
</select>
<select class="mdui-select mdui-p-x-2" id="pref-select" onchange="prefchange();confirm($other);">
    <option value="0" selected>地市</option>
</select>
<select class="mdui-select mdui-p-x-2" id="coun-select" onchange="confirm($other);" name="selectdivcode">
    <option value="0" selected>区县</option>
</select>
<script>setcode($value);</script>
EOF;
}