<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
</head>
<body>
    <h1>Test Company Update Form</h1>
    
    <form method="POST" action="{{ route('empresa.update') }}" id="testForm">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="Test Company" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="test@company.com" required><br><br>
        
        <label for="endereco">Endereço:</label>
        <textarea name="endereco" id="endereco" required>Test Address</textarea><br><br>
        
        <button type="submit">Submit Test</button>
    </form>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('Form method:', this.method);
            console.log('Form action:', this.action);
            const methodInput = this.querySelector('input[name="_method"]');
            console.log('Method input value:', methodInput ? methodInput.value : 'Not found');
            
            // Don't actually submit for testing
            e.preventDefault();
            alert('Form would submit with method: ' + (methodInput ? methodInput.value : 'POST'));
        });
    </script>
</body>
</html>
