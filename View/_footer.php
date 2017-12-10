<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/4/2017
 * Time: 8:21 AM
 */

if(isset($error) && !empty($error) && is_array($error)){
    foreach ($error as $property => $value)
    {
        echo "<br/><span style='color: red'>$value</span>";
    }
}

?>

    </div>

</div>
</body>
</html>