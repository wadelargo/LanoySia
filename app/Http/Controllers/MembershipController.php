<?php

namespace App\Http\Controllers;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembersImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Membership;
use App\Models\Type;
use Illuminate\Support\Facades\Log;

class MembershipController extends Controller
{
    public function index(Request $request) {
  
            $members = Membership::all();
    
        
        return view('members', compact('members'));
    }

    public function show(Membership $member)
    {
        
        return view('view', compact('member'));
    }

    public function generateCSV() {
        $members = Membership::orderBy('name')->get();
        
        $filename = storage_path('app/membership.csv'); 
        
        $file = fopen($filename, 'w+');
        
        // Define the CSV header
        fputcsv($file, ['name', 'description', 'type', 'price']);
        
        foreach ($members as $m) {
            fputcsv($file, [
                $m->name,
                $m->description,
                $m->type->name,
                $m->price,
            ]);
        }
        
        fclose($file);
        
        return response()->download($filename)->deleteFileAfterSend(true); // Delete file after sending response
    }
    

    public function importCsv(Request $request)
{
    // Validate the uploaded CSV file
    $request->validate([
        'csv-file' => 'required|file|mimes:csv,txt',
    ]);

    // Process the CSV file
    $file = $request->file('csv-file');

    // Read CSV data
    try {
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
    } catch (\Exception $e) {
        Log::error('Error reading CSV file: ' . $e->getMessage());
        return redirect()->route('members')->with('error', 'Error reading CSV file.');
    }

    // Log the header and first few rows of the CSV data for debugging
    if (empty($csvData)) {
        Log::error('CSV file is empty.');
        return redirect()->route('members')->with('error', 'CSV file is empty.');
    }

    $header = array_shift($csvData);
    Log::info('CSV Header: ' . implode(', ', $header));
    Log::info('First Row: ' . implode(', ', $csvData[0] ?? []));

    // Assuming the CSV structure is: name, description, type, price
    foreach ($csvData as $row) {
        // Check if row has the expected number of columns
        if (count($row) < 4) {
            Log::warning('Skipping row due to missing columns: ' . implode(',', $row));
            continue;
        }

        // Extract data from each row
        $name = $row[0];
        $description = $row[1];
        $typeName = $row[2];
        $price = $row[3];

        Log::info('Processing row: ' . implode(',', $row));

        // Find or create the membership type
        try {
            $type = Type::firstOrCreate(['name' => $typeName]);
        } catch (\Exception $e) {
            Log::error('Error finding or creating type: ' . $e->getMessage());
            continue;
        }

        // Create or update the membership
        try {
            Membership::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'type_id' => $type->id,
                    'price' => $price,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error creating or updating membership: ' . $e->getMessage());
        }
    }

    // Redirect or return a response as needed
    return redirect()->route('members')->with('message', 'Memberships imported successfully.');
}


    public function generatePDF() {
        $members = Membership::orderBy('name')->get();

        foreach ($members as $member) {
            $member->qrCode = QrCode::size(100)->generate($member->id);
        }

        $pdf = Pdf ::loadView('member-list', compact('members'));

        return $pdf->download('member-list.pdf');
    }

    


    // public function process(Request $request)
    // {
    //     $request->validate([
    //         'qr_code' => 'required|string',
    //     ]);

    //     $qrCode = $request->input('qr_code');

    //     return response()->json(['message' => 'QR code processed successfully', 'data' => $qrCode]);
    // }

};
    

