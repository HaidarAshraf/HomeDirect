<?php
require_once '../config.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv; //load api
use Dompdf\Dompdf; //generate pdfs

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$apiKey = $_ENV['OPENAI_API_KEY'] ?? null;   //load api
if (!$apiKey) {
    echo json_encode(['error' => 'API key missing.']);
    http_response_code(500);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);  //users message and stores
$message = trim($input['message'] ?? '');
$propertyId = $input['property_id'] ?? null;
session_start();

if (!$message) {
    echo json_encode(['error' => 'No input provided.']);
    http_response_code(400);
    exit();
}



$memory = $_SESSION['chat_memory'] ?? ['custom_clauses' => []];

// add users notes or claiuses they wanna add 
if (stripos($message, 'add') !== false || stripos($message, 'include') !== false) {
    $memory['custom_clauses'][] = $message;
    $_SESSION['chat_memory'] = $memory;
    echo json_encode(['reply' => "\u2705 Noted. I’ll include that in the agreement."]);
    exit();
}

$listingInfo = '';
$sellerName = '__________';
$propertyAddress = '__________';
$price = '__________';
$bedrooms = $bathrooms = $squareFeet = 'N/A';

if ($propertyId) {   // store property details for the ai chat bot to use 
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$propertyId]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($property) {
        $propertyAddress = "{$property['address']}, {$property['city']}, {$property['postcode']}";
        $price = "£" . number_format($property['price']);
        $bedrooms = $property['bedrooms'];
        $bathrooms = $property['bathrooms'];
        $squareFeet = $property['square_feet'];

        $listingInfo = "Property Address: $propertyAddress\nBedrooms: $bedrooms\nBathrooms: $bathrooms\nSquare Feet: $squareFeet\nPrice: $price\n";

        $userStmt = $conn->prepare("SELECT full_name AS name, email FROM users WHERE id = ?");
        $userStmt->execute([$property['user_id']]);
        $lister = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($lister) {
            $sellerName = $lister['name'];
        }
    }
}

// if user asks for agreement
if (preg_match('/\b(sale|lease)\s?agreement\b/i', $message)) {  
    $agreementText = <<<EOD
SALE AGREEMENT FOR RESIDENTIAL PROPERTY
This Agreement is made on: ________________________

1. PARTIES
Seller:
Full Name: $sellerName
Address: ________________________________________
Contact Number: ________________________
Email: ________________________

Buyer:
Full Name: ________________________________________
Address: ________________________________________
Contact Number: ________________________________________
Email: ________________________________________

2. PROPERTY DETAILS
$listingInfo
Land Registry Title Number: ________________________
Tenure: Freehold / Leasehold
Local Authority: ________________________

If leasehold:
Remaining Lease Term: ________________________
Ground Rent (annual): £__________
Service Charge (annual): £__________

3. PURCHASE PRICE AND PAYMENT TERMS
Total Agreed Purchase Price: $price
Deposit Amount (on exchange): £__________
Balance Payable on Completion: £__________

4. COMPLETION DATE
Completion shall take place on or before: ________________________

5. INCLUSIONS AND EXCLUSIONS
Includes: Fixtures and fittings (e.g., wardrobes, appliances)
Excludes: [e.g., decorative items]

6. LEGAL REPRESENTATION
Seller’s Solicitor: ________________________
Buyer’s Solicitor: ________________________

7. SURVEYS & INSPECTIONS
The Buyer has inspected the property or waived this right.

8. MORTGAGE & FUNDING
Buyer must secure funding prior to completion.

9. CONDITIONS OF SALE
Subject to Law Society Conditions (5th Ed.) and searches.

10. DEFAULT
Failure to complete can result in forfeiture of deposit or legal action.

11. SIGNATURES
Seller: ________________________   Date: ________________________
Buyer: ________________________   Date: ________________________

12. WITNESS (Recommended)
Witness Name: ________________________
Signature: ________________________
Date: ________________________
EOD;

    // add clase
    if (!empty($memory['custom_clauses'])) {
        $agreementText .= "\n\n13. CUSTOM CLAUSES";
        foreach ($memory['custom_clauses'] as $index => $clause) {
            $agreementText .= "\n" . ($index + 1) . ". $clause";
        }
    }
  
    //gentrate pdfs and show
    $pdf = new Dompdf();
    $pdf->set_option('isHtml5ParserEnabled', true);
    $pdf->loadHtml("<pre style='font-family: Inter, sans-serif; font-size: 12px;'>" . htmlentities($agreementText) . "</pre>");
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();

    $filename = "PropertyAgreement_" . time() . ".pdf";
    $pdfPath = __DIR__ . "/$filename";
    file_put_contents($pdfPath, $pdf->output());

    echo json_encode(['reply' => " Your agreement has been generated as a PDF.", 'pdf' => $filename]);
    exit;
}


$context = "You are a legal assistant. Keep answers short unless asked to generate a document.";
$response = chatGPT($apiKey, $context, $message);
echo json_encode(['reply' => $response['reply']]);

function chatGPT($apiKey, $systemPrompt, $userMessage) {
    $payload = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $userMessage]
        ],
        "temperature" => 0.7,
        "max_tokens" => 1000
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return [
        'reply' => $data['choices'][0]['message']['content'] ?? "Sorry, I couldn't generate a response."
    ];
}
