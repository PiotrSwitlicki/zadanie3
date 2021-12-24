<?php

namespace App\Modules\Importer\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Importer\Repositories\ImporterRepository;
use Illuminate\Config\Repository as Config;
use App\Modules\Importer\Http\Requests\ImporterRequest;
use Illuminate\Http\Response;
use App;
use App\Modules\Importer\Models\WorkOrder;
use App\Modules\Importer\Models\Importer;

/**
 * Class ImporterController
 *
 * @package App\Modules\Importer\Http\Controllers
 */
class ImporterController extends Controller
{
    /**
     * Importer repository
     *
     * @var ImporterRepository
     */
    private $importerRepository;

    /**
     * Set repository and apply auth filter
     *
     * @param ImporterRepository $importerRepository
     */
 /*   
    public function __construct(ImporterRepository $importerRepository)
    {
        $this->middleware('auth');
        $this->importerRepository = $importerRepository;
    }
*/
    /**
     * Return list of Importer
     *
     * @param Config $config
     *
     * @return Response
     */
    public function import()
    {
        $dom = new \DOMDocument();      
        libxml_use_internal_errors(true);       
    
        $dom->loadHTMLFile('work_orders.html');        
        
        $documentElement = $dom->documentElement; 
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('table');
        
        $rows = $tables->item(1)->getElementsByTagName('tr');
        //dd($rows);
        //$rows = \array_diff_key($rows, [0 => "xy", 1 => "xy", 2 => "xy", 3 => "xy", 4 => "xy", 5 => "xy"]);
        //dd($rows[6]->textContent);
        $Header = $dom->getElementsByTagName('th');
        $Detail = $dom->getElementsByTagName('td');

        foreach($Header as $NodeHeader) 
        {
                if($NodeHeader->textContent!="Needed Date")
                {
                    $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
                }
        }

        $i = 0;
        $j = 0;
        $aDataTableHeaderHTML = array_unique ($aDataTableHeaderHTML);
        //dd($aDataTableHeaderHTML);
        $start=0;
        $iterations=0;
        foreach($Detail as $sNodeDetail) 
        {   
            $iterations++;
            $stringlenght=strlen($sNodeDetail->textContent);
            
            if($stringlenght==8)
            {
                $start=1;
            }
            if($start==1)
            {


                $aDataTableDetailHTML[$j][$i] = trim($sNodeDetail->textContent);
                
               // $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
                if (strlen($aDataTableDetailHTML[$j][0]) != 8)
                {   
                   // dd(strlen($aDataTableDetailHTML[$j][0]));
                    unset($aDataTableDetailHTML[$j][0]);
                    $i=$i-1;
                    $start=0;
                }               
                if(isset($aDataTableDetailHTML[$j][1]))
                {
                    if (strpos(json_encode($aDataTableDetailHTML[$j][1]), '/', $offset = 0) == true) {                    
                        $aDataTableDetailHTML[$j][4]=$aDataTableDetailHTML[$j][1];
                        $aDataTableDetailHTML[$j][1]=" ";
                        $i=5;
                    }
                }
                if(isset($aDataTableDetailHTML[$j][12]))
                {
                    if (strlen($aDataTableDetailHTML[$j][12]) == 2)
                    {  
                        $aDataTableDetailHTML[$j][13]=$aDataTableDetailHTML[$j][12];
                        $aDataTableDetailHTML[$j][12]=" ";
                        $i=14;
                    } 
                }

                /*
                if(isset($aDataTableDetailHTML[$j][2]))
                {
                    if (strpos(json_encode($aDataTableDetailHTML[$j][2]), '/', $offset = 0) == true) {                    
                        $aDataTableDetailHTML[$j][5]=$aDataTableDetailHTML[$j][2];
                        $aDataTableDetailHTML[$j][2]=" ";
                        $i=5;
                    }
                }
                */
                $i = $i + 1;
                if($i==14){ $j++; $i=0; }

/*                $workorder = new WorkOrder;
               if (isset($aDataTableDetailHTML[$j])) {
                    if (array_key_exists('0', $aDataTableDetailHTML[$j])) {
                        $workorder->work_order_number = $aDataTableDetailHTML[$j][0];
                        unset($aDataTableDetailHTML[$j][0]);
                    }
                    if (array_key_exists('3', $aDataTableDetailHTML[$j])) {
                        $workorder->priority = $aDataTableDetailHTML[$j][3];
                        unset($aDataTableDetailHTML[$j][3]);
                    }
                    if (array_key_exists('8', $aDataTableDetailHTML[$j])) {
                        $workorder->category = $aDataTableDetailHTML[$j][8];
                        unset($aDataTableDetailHTML[$j][8]);
                    }
                    if (array_key_exists('10', $aDataTableDetailHTML[$j])) {
                        $workorder->fin_loc = $aDataTableDetailHTML[$j][10];
                        unset($aDataTableDetailHTML[$j][10]);
                        unset($aDataTableDetailHTML[$j]);
                    }
                    $workorder->save();
                }
*/
                

            }
        }
       
            $counter=1;
            $created=0;
            $skipped=0;



                     $fileName = 'csv.csv';
        
                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                
                $data = $aDataTableDetailHTML;

                    $fp = fopen('csv.csv', 'w');

                    
                   
                    $rowData = array();     
                  


       

            foreach ($aDataTableDetailHTML as $row)
            {
                $created++;
                //dd($row);
                $workordercheck = WorkOrder::where('work_order_number', '=',  $row[0])->first();
                if ($workordercheck === null)
                {
                    $workorder = new WorkOrder;
                    $workorder->work_order_number = $row[0];
                    if (array_key_exists('0', $row)) {
                         $workorder->work_order_number = $row[0];
                    //     unset($row[0]);
                    }
                    if (array_key_exists('3', $row)) {
                         $workorder->priority = $row[3];
                    //     unset($row[3]);
                    }
                    if (array_key_exists('4', $row)) {
                         $workorder->received_date = $row[4];
                    //     unset($row[4]);
                    }
                    if (array_key_exists('8', $row)) {
                         $workorder->category = $row[8];
                    //     unset($row[8]);
                    }
                    if (array_key_exists('10', $row)) {
                        $workorder->fin_loc = $row[10];
                    //    unset($row[10]);
                    }
                    $workorder->save();
                    
                   

                    foreach ($row as $item) {
                            $rowData[] = $item;
                            $rowData[15] = 'created';

                        }
                        fputcsv($fp, array_values($rowData),  ';', ' ');
                        unset($rowData);
                    unset($row);
                }
                else
                {
                    foreach ($row as $item) {
                            $rowData[] = $item;
                            $rowData[15] = 'skipped';

                        }
                        fputcsv($fp, array_values($rowData),  ';', ' ');
                        unset($rowData);

                    $skipped++;
                    $created--;
                    unset($row);
                }

               
            }

        $importer = new Importer;
        $importer->type = "HTML";               
        $importer->entries_processed = $iterations;
        $importer->entries_created = $created;
        $importer->run_at = date("Y/m/d") . " " . date("h:i:sa");
        $importer->save();

        $imports = Importer::all();
        $imports = $imports->toArray();

        $workorders = Workorder::all();
        $workorders = $workorders->toArray();
        //dd($imports);
            //die();

        foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
         //       dd($cols[6]->textContent);
        }

      //  dd($dom);
            return view('welcome', [ 'importer' => $importer, 'workorder' => $aDataTableDetailHTML, 'imports' => $imports, 'workorders' => $workorders]); 
    }

    public function index(Config $config)
    {
        $this->checkPermissions(['importer.index']);
        $onPage = $config->get('system_settings.importer_pagination');
        $list = $this->importerRepository->paginate($onPage);

        return response()->json($list);
    }

    /**
     * Display the specified Importer
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->checkPermissions(['importer.show']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id));
    }

    /**
     * Return module configuration for store action
     *
     * @return Response
     */
    public function create()
    {
        $this->checkPermissions(['importer.store']);
        $rules['fields'] = $this->importerRepository->getRequestRules();

        return response()->json($rules);
    }

    /**
     * Store a newly created Importer in storage.
     *
     * @param ImporterRequest $request
     *
     * @return Response
     */
    public function store(ImporterRequest $request)
    {
        $this->checkPermissions(['importer.store']);
        $model = $this->importerRepository->create($request->all());

        return response()->json(['item' => $model], 201);
    }

    /**
     * Display Importer and module configuration for update action
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id, true));
    }

    /**
     * Update the specified Importer in storage.
     *
     * @param ImporterRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(ImporterRequest $request, $id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        $record = $this->importerRepository->updateWithIdAndInput($id,
            $request->all());

        return response()->json(['item' => $record]);
    }

    /**
     * Remove the specified Importer from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->checkPermissions(['importer.destroy']);
        App::abort(404);
        exit;

        /* $id = (int) $id;
        $this->importerRepository->destroy($id); */
    }
}
