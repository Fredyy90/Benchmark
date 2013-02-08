<?php
namespace Fredyy90;
/**
 * Simple class to benchmark code
 */
class Benchmark
{
    /**
     * This will contain the results of the benchmarks.
     * There is no distinction between averages and just one runs
     */
    private $_results = array();

    /**
     * Disable PHP's time limit and PHP's memory limit!
     * These benchmarks may take some resources
     */
    public function __construct() {
        set_time_limit( 0 );
        ini_set('memory_limit', '1024M');
    }

    /**
     * The function that times a piece of code
     * @param string $name Name of the test. Must not have been used before
     * @param callable|closure $callback A callback for the code to run.
     * @param boolean|integer $multiple optional How many times should the code be run,
     * if false, only once, else run it $multiple times, and store the average as the benchmark
     * @return Benchmark $this
     */
    public function time( $name, $callback, $multiple = false )
    {
        if($multiple === false) {
            // run and time the test
            $start = microtime( true );
            $callback();
            $end = microtime( true );

            // add the results to the results array
            $this->_results[] = array(
                'test' => $name,
                'iterations' => 1,
                'time' => $end - $start
            );
        } else {
            // set a default if $multiple is set to true
            if($multiple === true) {
                $multiple = 10000;
            }

            // run the test $multiple times and time it every time
            $total_time = 0;
            for($i=1;$i<=$multiple;$i++) {
                $start = microtime( true );
                $callback();
                $end = microtime( true );
                $total_time += $end - $start;
            }
            // calculate the average and add it to the results
            $this->_results[] = array(
                'test' => $name,
                'iterations' => $multiple,
                'avg_time' => $total_time/$multiple,
                'total_time' => $total_time
            );
        }
        return $this; //chainability
    }

    /**
     * Returns all the results
     * @return array $results
     */
    public function get_results()
    {
        return $this->_results;
    }

    /**
     * Returns extended and sorted results
     * @param bool $formatFloats true if you want all floats converted to fancier looking string
     * @return array $results
     */
    public function get_extended_results($formatFloats = false)
    {
        $results = $this->_results;

        usort($results, function($a, $b) {
            return ($a['total_time'] == $b['total_time']) ? 0 : ( ($a['total_time'] < $b['total_time']) ? -1 : 1 );
        });

        array_walk($results, function(&$result) use ($results) {
            $result['percent']      = $result['total_time'] / $results['0']['total_time'] * 100;
            $result['percent_diff'] = $result['percent'] - 100;
        });

        if($formatFloats == true){
            $self = $this; // Workaround to use $this in closure
            array_walk($results, function(&$result) use ($self) {
                $result['avg_time']     = $self->format_floats($result['avg_time'], 10)."s";
                $result['total_time']   = $self->format_floats($result['total_time'], 10)."s";
                $result['percent']      = $self->format_floats($result['percent'])."%";
                $result['percent_diff'] = $self->format_floats($result['percent_diff'])."%";
            });
        }

        return $results;
    }

    /**
     * Format floats for a prettier look in the table
     * @param float $num Float to format
     * @param int $decimals Count of decimals to show
     * @return string $num
     */
    public function format_floats($num, $decimals = 5)
    {
        return number_format($num, $decimals, '.', '');
    }

    /**
     * Returns all the results in a simple html table
     * @return string $table
     */
    public function get_results_table()
    {
        $results = $this->get_extended_results(true);
        $table   = $this->array_to_table($results);

        return $table;
    }

    /**
     * Transforms a 2D-array to a simple html table
     * @return string $table
     */
    public function array_to_table($array)
    {
        $table  = "<style>
                        table.benchmarkresult{
                            border-collapse: collapse;
                            border: 5px ridge #98bf21;
                        }
                        table.benchmarkresult tr.heading th{
                            padding : 5px 10px;
                            font-size: 150%;
                            text-align : center;
                        }
                        table.benchmarkresult th, table.benchmarkresult td{
                            padding : 5px 10px;
                            border: 1px solid #666;
                            text-align : left;
                        }
                        table.benchmarkresult tr > td:nth-child(n+2){
                            text-align : right;
                            font-family: monospace;
                        }
                   </style>";
        $table  .= "<table class='benchmarkresult'>
                    <thead>
                       <tr class='heading'>
                            <th colspan='".count(current($array))."'>Benchmark results</th>
                       </tr>
                       <tr>";

            foreach(array_keys(current($array)) as $name) {
                $table .= "<th>{$name}</th>";
            }

        $table  .= "   </tr>
                    </thead>
                    <tbody>";

        foreach($array as $row) {
            $table .= "<tr>";

            foreach($row as $cell) {
                $table .= "<td>{$cell}</td>";
            }

            $table .= "</tr>";
        }

        $table .= "</tbody>
                   </table>";

        return $table;

    }
}

?>