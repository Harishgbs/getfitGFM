<?php
include 'dbcon.php';
require('./vendor/autoload.php'); // Use Composer's autoload

if (!$conn) {
    die("Connection error");
}

if (isset($_POST['submit'])) {
    // Retrieve all form data
    $transaction_id = uniqid("TXN");  // generate a unique transaction ID
    $rollNo = $_POST['roll'];
    $studentName = $_POST['studentname']; // Corrected key
    $fatherName = $_POST['fatherName'];
    $course = $_POST['course'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $fee = $_POST['fee'];
    $months = $_POST['months'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];

    // Insert query
    $sql = "INSERT INTO mess_payments (
                transaction_id, roll_number, student_name, father_name,
                course, branch, year_of_study, mobile_number,
                email_id, monthly_fee, num_months, amount, remarks
            ) VALUES (
                '$transaction_id', '$rollNo', '$studentName', '$fatherName',
                '$course', '$branch', '$year', '$mobile',
                '$email', '$fee', '$months', '$amount', '$remarks'
            )";

    $res = mysqli_query($conn, $sql);

    if (!$res) {
        echo "Error while inserting data!";
    } else {
        // Create receipts directory if it doesn't exist
        if (!file_exists('receipts')) {
            mkdir('receipts', 0777, true);
        }

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Payment Receipt', 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Transaction ID: $transaction_id", 0, 1);
        $pdf->Cell(0, 10, "Roll No: $rollNo", 0, 1);
        $pdf->Cell(0, 10, "Name: $studentName", 0, 1);
        $pdf->Cell(0, 10, "Father Name: $fatherName", 0, 1);
        $pdf->Cell(0, 10, "Course: $course", 0, 1);
        $pdf->Cell(0, 10, "Branch: $branch", 0, 1);
        $pdf->Cell(0, 10, "Year: $year", 0, 1);
        $pdf->Cell(0, 10, "Mobile: $mobile", 0, 1);
        $pdf->Cell(0, 10, "Email: $email", 0, 1);
        $pdf->Cell(0, 10, "Monthly Fee: ₹$fee", 0, 1);
        $pdf->Cell(0, 10, "Number of Months: $months", 0, 1);
        $pdf->Cell(0, 10, "Total Amount: ₹$amount", 0, 1);
        $pdf->Cell(0, 10, "Remarks: $remarks", 0, 1);

        // Output the PDF to a file
        $pdfFilePath = "receipts/receipt_$transaction_id.pdf"; // Ensure the 'receipts' directory exists
        $pdf->Output('F', $pdfFilePath);

        // Provide download link
        echo "<script>alert('Payment recorded! Transaction ID: $transaction_id'); window.location.href='$pdfFilePath';</script>";
    }
}
?>