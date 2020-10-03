<?php

namespace App\Http\Controllers;

use App\failedJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


use Exception;


/**
 * toolbox controller, functions to speed up setting up a new project
 *
 *
 * @warning should not be live, debug = TRUE
 */
class devToolBoxController extends Controller
{

    /**
     * generates models from loaded database
     */
    public function modelMaker()
    {


        $tables = DB::select('show tables');
        $sd = scandir('app');

        echo 'Generated: <hr>';

        foreach ($tables as $t) {


            $table_names = explode('_', $t->Tables_in_golf);
            $table_name = null;

            foreach ($table_names as $tn) {
                $table_name = $table_name . ucfirst($tn);
            }

            $table_name = lcfirst($table_name);

            $here = 0;

            foreach ($sd as $f) {
                if ($f == $table_name . '.php') {
                    $here = 1;
                }
            }

            if (!$here) {

                $sql = "select *
                        from information_schema.columns
                        where table_schema = 'brighton_boys'
                        and table_name = '" . $t->Tables_in_golf . "'
                        ORDER BY ORDINAL_POSITION ASC";

                $table = DB::select($sql);
                $docbl = '';

                foreach ($table as $row) {

                    $datatype = 'int';

                    if ($row->DATA_TYPE == 'mediumtext' || $row->DATA_TYPE == 'varchar') {
                        $datatype = 'string';
                    }

                    if ($row->DATA_TYPE == 'date' || $row->DATA_TYPE == 'datetime') {
                        $datatype = 'datetime';
                    }

                    $primary = NULL;
                    if ($row->COLUMN_KEY == 'PRI') {
                        $primary = 'PRIMARY_KEY';
                    }

                    $param = "*@param " . strtoupper($datatype) . " " . $row->COLUMN_NAME . ' ' . $primary . '
';

                    $docbl .= $param;
                }

                $output = "<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* model for quiz:$t->Tables_in_golf
*
$docbl*/
class $table_name extends Model {

    public " . '$timestamps' . " = false;
    protected " . '$table' . " = '$t->Tables_in_golf';

}";

                echo $table_name;
                echo '<br>';
                $fp = fopen("app/$table_name.php", 'w');
                fwrite($fp, $output);
                fclose($fp);
            }
        }

        echo "</body></html>";
    }
}
