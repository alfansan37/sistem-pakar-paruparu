// ===== SYSTEM PACKAGE DIAGNOSA PARU-PARU - SCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== BACK TO TOP BUTTON =====
    const backToTop = document.createElement('button');
    backToTop.innerHTML = '<i class="fas fa-chevron-up"></i>';
    backToTop.className = 'back-to-top';
    backToTop.setAttribute('aria-label', 'Kembali ke atas');
    document.body.appendChild(backToTop);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
    
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // ===== SYMPTOM SELECTION ENHANCEMENT =====
    const symptomItems = document.querySelectorAll('.symptom-item');
    symptomItems.forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('selected', checkbox.checked);
            
            // Update counter if exists
            updateSelectedSymptomsCount();
        });
    });
    
    function updateSelectedSymptomsCount() {
        const counter = document.getElementById('selectedCount');
        if (counter) {
            const selected = document.querySelectorAll('.symptom-item.selected').length;
            counter.textContent = selected;
        }
    }
    
    // ===== FORM VALIDATION ENHANCEMENT =====
    const forms = document.querySelectorAll('form[novalidate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Add Bootstrap validation classes
                const inputs = this.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });
            }
            
            this.classList.add('was-validated');
        });
    });
    
    // Real-time validation
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
    
    // ===== DASHBOARD CHARTS (if needed) =====
    function initCharts() {
        const chartElements = document.querySelectorAll('[data-chart]');
        chartElements.forEach(element => {
            const chartType = element.getAttribute('data-chart');
            const chartData = JSON.parse(element.getAttribute('data-chart-data'));
            
            // Simple bar chart using CSS
            if (chartType === 'bar' && chartData.values) {
                createSimpleBarChart(element, chartData);
            }
        });
    }
    
    function createSimpleBarChart(element, data) {
        const maxValue = Math.max(...data.values);
        const barsHTML = data.labels.map((label, index) => `
            <div class="chart-bar-container mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="chart-label">${label}</span>
                    <span class="chart-value">${data.values[index]}</span>
                </div>
                <div class="chart-bar bg-primary rounded" 
                     style="width: ${(data.values[index] / maxValue) * 100}%">
                </div>
            </div>
        `).join('');
        
        element.innerHTML = barsHTML;
    }
    
    // ===== LOADING STATES =====
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.form && this.form.checkValidity()) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                this.disabled = true;
            }
        });
    });
    
    // ===== AUTO-HIDE ALERTS =====
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // ===== PRINT FUNCTIONALITY =====
    const printButtons = document.querySelectorAll('[data-print]');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-print');
            const element = target ? document.querySelector(target) : document.body;
            
            if (element) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Cetak Hasil Diagnosa</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .no-print { display: none !important; }
                            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
                            @media print {
                                .no-print { display: none !important; }
                            }
                        </style>
                    </head>
                    <body>
                        ${element.innerHTML}
                        <script>
                            window.onload = function() { window.print(); }
                        <\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            }
        });
    });
    
    // ===== SEARCH AND FILTER =====
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const target = this.getAttribute('data-search');
            const items = document.querySelectorAll(target);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
    
    // ===== INITIALIZE COMPONENTS =====
    initCharts();
    updateSelectedSymptomsCount();
    
    console.log('Sistem Pakar Paru-Paru - Script loaded successfully');
});

// ===== UTILITY FUNCTIONS =====

// Format tanggal Indonesia
function formatDateID(dateString) {
    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

// Format persentase
function formatPercentage(value, decimals = 2) {
    return `${value.toFixed(decimals)}%`;
}

// Debounce function untuk search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}