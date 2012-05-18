<div id="container">
    <h2>
        <?=$pageTitle?>
    </h2>
    <h3>
        DB results
    </h3>
    <p>
        <?php 
        foreach($DBtest as $data) {
            foreach($data as $data2) {
                foreach($data2 as $val) {
                    echo $val. "<br />";
                }
                echo "<br />";
            }
        }        
        ?>
    </p>
</div>