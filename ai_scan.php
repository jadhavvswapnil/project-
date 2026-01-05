<?php include 'includes/header.php'; ?>

<div class="container section-padding">
    <div style="text-align: center; max-width: 600px; margin: 0 auto;">
        <h2 class="section-title">AI Plant Doctor <i class="fa-solid fa-user-doctor"></i></h2>
        <p style="margin-bottom: 30px;">Upload a photo of your plant, and our AI will diagnose any diseases and suggest treatments.</p>
        
        <div class="auth-box" id="upload-box" style="width: 100%;">
            <div style="border: 2px dashed var(--border-color); padding: 40px; border-radius: 10px; margin-bottom: 20px; cursor: pointer;" onclick="document.getElementById('plant-input').click()">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 10px;"></i>
                <p>Click to Upload or Drag & Drop</p>
                <input type="file" id="plant-input" style="display: none;" accept="image/*" onchange="previewImage(this)">
            </div>
            
            <div id="preview-area" style="display: none; margin-bottom: 20px;">
                <img id="preview-img" src="" style="max-width: 100%; border-radius: 10px; margin-bottom: 10px;">
                <button class="btn btn-primary" onclick="analyzePlant()">Analyze Plant</button>
            </div>

            <div id="result-area" style="display: none; text-align: left; background: #e8f5e9; padding: 20px; border-radius: 10px; margin-top: 20px;">
                <h3 style="color: #2e7d32; margin-bottom: 10px;"><i class="fa-solid fa-check-circle"></i> Diagnosis Complete</h3>
                <p><strong>Detected Issue:</strong> <span id="issue-text">Scanning...</span></p>
                <p><strong>Confidence:</strong> <span id="confidence-text">98%</span></p>
                <hr style="margin: 10px 0;">
                <h4>Recommended Treatment:</h4>
                <p id="treatment-text">Isolate the plant and apply neem oil every 7 days. Ensure proper air circulation.</p>
                <br>
                <a href="shop.php?category=fertilizers" class="btn btn-outline" style="font-size: 0.9rem;">Buy Treatment</a>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('upload-box').querySelector('div').style.display = 'none';
            document.getElementById('preview-area').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function analyzePlant() {
    var btn = document.querySelector('#preview-area button');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analyzing...';
    btn.disabled = true;
    
    // Simulate API delay
    setTimeout(function() {
        document.getElementById('result-area').style.display = 'block';
        btn.innerHTML = 'Assessed';
        
        // Randomize result for demo
        const issues = ["Leaf Blight", "Root Rot", "Mealybugs", "Aphids", "Healthy Plant"];
        const randomIssue = issues[Math.floor(Math.random() * issues.length)];
        
        document.getElementById('issue-text').innerText = randomIssue;
        
        if(randomIssue === "Healthy Plant") {
             document.getElementById('treatment-text').innerText = "Your plant looks great! Keep up the good work.";
             document.getElementById('result-area').style.background = "#e8f5e9";
        } else {
             document.getElementById('treatment-text').innerText = "Isolate the plant immediately. Use organic fungicide or insecticide as appropriate.";
             document.getElementById('result-area').style.background = "#fff3e0";
        }
        
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?>
