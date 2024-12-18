<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Terms and Conditions</title>
    <style>
        body {
            font-family: 'Halvetica', sans-serif;
            margin: 20px;
            background: #f5f5f9;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
        }
        h1 {
            text-align: center;
        }

        .sub-title{
            margin-top: 10px;
            margin-bottom: 20px;
        }

        h3{
            text-justify: auto;

        }

        .terms-condition-content{
            margin: 10px;
        }
        .list{
            margin-left: 20px;
        }

        .btn{
            justify-content: center;
            float: right;
        }

        button {
            display: block;
            margin:43px -17px;
            padding: 10px 20px;
            width: 100px;
            font-size: 18px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        a {
            text-decoration: none; 
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Terms and Conditions</h1>
        <h3 class="sub-title">
            By creating an account on DATOS, you agree to the following terms and conditions. Please read them carefully before proceeding.
        </h3>
        <div class="terms-condition-content">
            <h3>
                1. Account Registration
            </h3>
            <p>
                1.1. You must provide accurate and complete information when creating an account.
            </p>
            <p>
                1.2. You are responsible for maintaining the confidentiality of your account credentials.
            </p>
            <p>
                1.3. You agree not to share your account with others or allow unauthorized access.  
            </p>
        </div>

        <div class="terms-condition-content">
            <h3>
                2. Use of Services
            </h3>
            <p>
                2.1. Our system stores digital copies of the University Documents for University purposes such as retrieval and and backtracking.            </p>
            <p>
                2.2. You must ensure that all uploaded documents comply with applicable requirements and do not contain prohibited or harmful content.            </p>
            <p>
                2.3. You agree not to use the service for any illegal or unauthorized activities.
            </p>
        </div>

        <div class="terms-condition-content">
            <h3>
                3. Data Privacy
            </h3>
            <p>
                3.1. We prioritize the security of your data. Please refer to our [Privacy Policy] for details on how we collect, store, and protect your information.
            <p>
                3.2. You are responsible for keeping backups of the University's original documents.
            </p>
        </div>

        <div class="terms-condition-content">
            <h3>
                4. Intellectual Property
            </h3>
            <p>
                4.1. The University retain ownership of the documents you upload to the system.            
            </p>
            <p>
                4.2. By uploading documents, you grant us a limited license to store and process them solely for providing the service. 
            </p>
        </div>


        <div class="terms-condition-content">
            <h3>
                5. Limitations of Liability
            </h3>
            <p>
                5.1. While we take all reasonable measures to protect your data, we cannot guarantee uninterrupted service or absolute security.            
            </p>
            <p>
                5.2. We are not liable for any loss, damage, or unauthorized access to the documents caused by external factors or misuse of the service.            
            </p>
        </div>

        <div class="terms-condition-content">
            <h3>
                6. Prohibited Content
            </h3>
            <p>
                6.1. You agree not to upload documents that:            
            </p>
            <ol class="list">
                <p>a. Violate any laws or regulations.</p>
                <p>b. Contain offensive, defamatory, or harmful material.</p>
                <p>c. Infringe on the intellectual property rights of others.</p>
            </ol>
        </div>

        <div class="terms-condition-content">
            <h3>
                7. Termination of Account
            </h3>
            <p>
                7.1. We reserve the right to suspend or terminate your account if you violate these terms.            
            </p>
            <p>
                7.2. Upon termination, your data may be deleted, and access to the system will be revoked.            
            </p>
        </div>

        

        <div class="terms-condition-content">
            <h3>
                8. Modifications to Terms
            </h3>
            <p>
                8.1. We may update these Terms and Conditions from time to time.            
            </p>
            <p>
                8.2. Continued use of the service after changes have been made constitutes your acceptance of the revised terms.            
            </p>
        </div>

        <div class="terms-condition-content">
            <h3>
                9.  Contact Us
            </h3>
            <p>
                If you have any questions about these terms, please contact us at datos.bu@gmail.com.            
            </p>
        </div>
        <div class="btn">
            <a href="{{ route('register') }}">
                <button>Back</button>
            </a>
        </div>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>