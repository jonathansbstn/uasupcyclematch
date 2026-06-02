$base = "http://127.0.0.1:8001/api/v1"
$h = @{ "Accept" = "application/json"; "Content-Type" = "application/json" }

Write-Host "=== TEST 1: Register ===" -ForegroundColor Cyan
$body = '{"name":"API Tester","email":"apitest@upcyclematch.id","password":"password123","password_confirmation":"password123","role":"contributor"}'
try {
    $r = Invoke-WebRequest -Uri "$base/auth/register" -Method POST -Headers $h -Body $body -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK STATUS:$($r.StatusCode) | Token:$($j.token.Substring(0,40))..." -ForegroundColor Green
    $global:tok = $j.token
} catch {
    $msg = $_.ErrorDetails.Message
    Write-Host "SKIP (sudah ada): $msg" -ForegroundColor Yellow
}

Write-Host "=== TEST 2: Login JWT ===" -ForegroundColor Cyan
$body = '{"email":"apitest@upcyclematch.id","password":"password123"}'
try {
    $r = Invoke-WebRequest -Uri "$base/auth/login" -Method POST -Headers $h -Body $body -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK STATUS:$($r.StatusCode) | token_type:$($j.token_type) | expires_in:$($j.expires_in)s | role:$($j.user.role)" -ForegroundColor Green
    Write-Host "TOKEN: $($j.access_token.Substring(0,60))..." -ForegroundColor Green
    $global:tok = $j.access_token
} catch {
    Write-Host "FAIL: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "=== TEST 3: Basic Auth Login ===" -ForegroundColor Cyan
$cred = [Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes("apitest@upcyclematch.id:password123"))
$bh = @{ "Accept"="application/json"; "Content-Type"="application/json"; "Authorization"="Basic $cred" }
try {
    $r = Invoke-WebRequest -Uri "$base/auth/login" -Method POST -Headers $bh -Body '{}' -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK Basic Auth! Token: $($j.access_token.Substring(0,40))..." -ForegroundColor Green
} catch {
    Write-Host "INFO: $($_.Exception.Message)" -ForegroundColor Yellow
}

Write-Host "=== TEST 4: GET /auth/me (JWT Protected) ===" -ForegroundColor Cyan
if ($global:tok) {
    $ah = @{ "Accept"="application/json"; "Authorization"="Bearer $global:tok" }
    try {
        $r = Invoke-WebRequest -Uri "$base/auth/me" -Method GET -Headers $ah -UseBasicParsing
        $j = $r.Content | ConvertFrom-Json
        Write-Host "OK STATUS:$($r.StatusCode) | name:$($j.name) | role:$($j.role) | koin:$($j.koin)" -ForegroundColor Green
    } catch {
        Write-Host "FAIL: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "=== TEST 5: GET /textiles (Publik, no auth) ===" -ForegroundColor Cyan
try {
    $r = Invoke-WebRequest -Uri "$base/textiles?per_page=3" -Method GET -Headers @{ "Accept"="application/json" } -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK STATUS:$($r.StatusCode) | Total limbah: $($j.meta.total)" -ForegroundColor Green
} catch {
    Write-Host "FAIL: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "=== TEST 6: GET /analytics (Publik) ===" -ForegroundColor Cyan
try {
    $r = Invoke-WebRequest -Uri "$base/analytics" -Method GET -Headers @{ "Accept"="application/json" } -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK STATUS:$($r.StatusCode) | weight:$($j.summary.total_weight)kg | products:$($j.summary.total_products)" -ForegroundColor Green
} catch {
    Write-Host "FAIL: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "=== TEST 7: API Key - /public/stats ===" -ForegroundColor Cyan
$kh = @{ "Accept"="application/json"; "X-API-KEY"="upcyclematch-dev-key-2026" }
try {
    $r = Invoke-WebRequest -Uri "$base/public/stats" -Method GET -Headers $kh -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    Write-Host "OK STATUS:$($r.StatusCode) | savings:Rp$($j.summary.material_savings)" -ForegroundColor Green
} catch {
    Write-Host "FAIL: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "=== TEST 8: API Key INVALID (expect 401) ===" -ForegroundColor Cyan
try {
    $r = Invoke-WebRequest -Uri "$base/public/stats" -Method GET -Headers @{ "Accept"="application/json"; "X-API-KEY"="wrong-key" } -UseBasicParsing
    Write-Host "WARN: Should be 401 but got $($r.StatusCode)" -ForegroundColor Yellow
} catch {
    Write-Host "OK Correctly rejected invalid key - $($_.Exception.Response.StatusCode)" -ForegroundColor Green
}

Write-Host "=== TEST 9: No token on protected route (expect 401) ===" -ForegroundColor Cyan
try {
    $r = Invoke-WebRequest -Uri "$base/auth/me" -Method GET -Headers @{ "Accept"="application/json" } -UseBasicParsing
    Write-Host "WARN: Should be 401 but got $($r.StatusCode)" -ForegroundColor Yellow
} catch {
    Write-Host "OK Correctly rejected - $($_.Exception.Response.StatusCode)" -ForegroundColor Green
}

Write-Host "=== SEMUA TEST SELESAI ===" -ForegroundColor Magenta
