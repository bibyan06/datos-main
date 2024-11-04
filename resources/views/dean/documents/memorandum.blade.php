@extends('layouts.dean_layout')

@section('title', 'Memorandum' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/dean/memorandum.css') }}">
@endsection

@section('main-id','memorandum-content')

@section('content') 
        <main id="memorandum-content">
            <div class="memorandum-container">
                <div class="memorandum-title">
                    <h1>MEMORANDUM</h1>
                </div>
                <div class="left-content">
                    <div class="memorandum-search-bar">
                        <input type="text" id="search-text" class="search-text" placeholder="Search Document">
                        <div class="icon"><i class="bi bi-search"></i></div>
                    </div>
                    <div class="documents-option">
                        <div class="search">
                            <select id="option-text" class="option-text">
                                <option value="" selected>Select Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="dashboard-section">
                <div class="dashboard-container">
                    <div class="documents"  id="documents-list">
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>                        
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="document">
                            <div class="file-container">
                                <div class="document-card">
                                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                            <div class="document-description">
                                <div class="row">
                                        <div class="column-left">
                                            <h3>
                                            Office Memorandum No. 84
                                            </h3>
                                        </div>
                                        <div class="column-right">
                                            <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                            <div class="dropdown-more">
                                                <a href="dean_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="dean_edit.html">Edit</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: April 2, 2024</p>
                                    <p>Details of the memorandum go here.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add more documents as needed -->
                    </div>
                </div>
            </div>
@endsection
 

@section('custom-js')
    <script src="{{ asset ('js/dean/memorandum.js') }}"></script>
@endsection

</body>
</html>