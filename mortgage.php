<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mortgage Calculator</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="/FinalYearProject/navbar.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="mortgage-section">
  <div class="text-center mb-8">
    <h1 class="text-3xl font-bold mb-2">Mortgage Calculator</h1>
    <p class="text-muted-foreground">Estimate your monthly mortgage payments based on home price, down payment, interest rate, and loan term.</p>
  </div>

  <div class="mortgage-grid">
    <!-- Left: Inputs -->
    <div class="card">
      <div class="card-title">Mortgage Calculator</div>

      <div class="form-group">
        <label for="homePrice">Home Price</label>
        <input type="number" id="homePrice" value="500000" />
      </div>

      <div class="form-group">
        <label for="downPayment">Down Payment</label>
        <input type="number" id="downPayment" value="100000" />
      </div>

      <div class="form-group">
        <label for="interestRate">Interest Rate (%)</label>
        <input type="number" id="interestRate" step="0.1" value="4.5" />
      </div>

      <div class="form-group">
        <label for="loanTerm">Loan Term (Years)</label>
        <input type="number" id="loanTerm" value="30" />
      </div>
    </div>

    <!-- Right: Results -->
    <div class="card">
      <div class="card-title">Monthly Payment</div>
      <div class="text-center">
        <p id="monthlyPayment" class="text-3xl font-bold mb-2">£0</p>
        <p class="text-sm text-muted-foreground">Principal & Interest</p>
      </div>
      <div class="result-item">
        <span>Loan Amount:</span>
        <span class="result-value" id="loanAmount">£0</span>
      </div>
      <div class="result-item">
        <span>Total Interest:</span>
        <span class="result-value" id="totalInterest">£0</span>
      </div>
      <div class="result-item">
        <span>Total Payment:</span>
        <span class="result-value" id="totalPayment">£0</span>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<style>
.mortgage-section {
  max-width: 900px;
  margin: 5rem auto;
  padding: 2rem;
}
.mortgage-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}
@media (min-width: 768px) {
  .mortgage-grid {
    grid-template-columns: 2fr 1fr;
  }
}
.card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.card-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.form-group {
  margin-bottom: 1.5rem;
}
.form-group label {
  font-size: 0.875rem;
  font-weight: 500;
  display: block;
  margin-bottom: 0.5rem;
}
.form-group input[type="number"] {
  width: 100%;
  padding: 10px;
  font-size: 0.95rem;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
}
.result-item {
  display: flex;
  justify-content: space-between;
  font-size: 0.875rem;
  margin: 0.75rem 0;
}
.result-value {
  font-weight: 600;
}
.text-center {
  text-align: center;
}
</style>

<script>
function formatCurrency(num) {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
    maximumFractionDigits: 0
  }).format(num);
}

function calculateMortgage() {
  const homePrice = parseFloat(document.getElementById('homePrice').value) || 0;
  const downPayment = parseFloat(document.getElementById('downPayment').value) || 0;
  const interestRate = parseFloat(document.getElementById('interestRate').value) || 0;
  const loanTerm = parseInt(document.getElementById('loanTerm').value) || 0;

  const loanAmount = homePrice - downPayment;
  const monthlyRate = interestRate / 100 / 12;
  const numberOfPayments = loanTerm * 12;

  let monthlyPayment = 0;
  if (monthlyRate === 0) {
    monthlyPayment = loanAmount / numberOfPayments;
  } else {
    monthlyPayment = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) /
                     (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
  }

  const totalPayment = monthlyPayment * numberOfPayments;
  const totalInterest = totalPayment - loanAmount;

  document.getElementById('monthlyPayment').textContent = formatCurrency(monthlyPayment);
  document.getElementById('loanAmount').textContent = formatCurrency(loanAmount);
  document.getElementById('totalInterest').textContent = formatCurrency(totalInterest);
  document.getElementById('totalPayment').textContent = formatCurrency(totalPayment);
}

document.querySelectorAll('#homePrice, #downPayment, #interestRate, #loanTerm')
  .forEach(input => input.addEventListener('input', calculateMortgage));

calculateMortgage();
</script>

</body>
</html>
