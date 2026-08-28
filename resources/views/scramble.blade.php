<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('scramble.ui.title', 'API Docs') }}</title>
    
    <script src="https://unpkg.com/@stoplight/elements/web-components.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@stoplight/elements/styles.min.css">
    <style>
        body { height: 100vh; margin: 0; }
    </style>
</head>
<body>

<elements-api
    id="docs"
    apiDescriptionUrl="{{ route('scramble.docs.document', config('scramble.api_path', 'default')) }}"
    router="hash"
    layout="responsive"
    hideSchemas="false"
/>

<script>
    // Interceptor: Menangkap response dari request Try-It di browser
    const originalFetch = window.fetch;
    window.fetch = async function(...args) {
        const response = await originalFetch.apply(this, args);
        
        try {
            const url = args[0] ? (typeof args[0] === 'string' ? args[0] : args[0].url) : '';
            
            // Cek jika request mengarah ke endpoint login
            if (url.includes('/auth/login') && response.ok) {
                const clone = response.clone();
                const data = await clone.json();
                
                // Ambil token (sesuaikan key token di response Anda, misal: data.token / data.access_token / data.data.token)
                const token = data.token || data.access_token || (data.data && data.data.token);
                
                if (token) {
                    console.log('✅ Auto-Token Captured:', token);
                    localStorage.setItem('scramble_bearer_token', token);
                    
                    // Notifikasi visual kecil di pojok layar
                    alert('Token login berhasil disimpan otomatis!');
                }
            }
        } catch (e) {
            console.error('Error auto-capturing token:', e);
        }

        return response;
    };
</script>

</body>
</html>
