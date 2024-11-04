
@extends('layouts.admin_layout')

@section('title', 'View All Documents' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/admin_all_docs.css') }}">
@endsection

@section('main-id','memorandum-content')

@section('content') 
        <div class="memorandum-container">
            <div class="memorandum-title">
                <h1>ALL DIGITIZED DOCUMENTS</h1>
            </div>
            <div class="left-content">
                <div class="memorandum-search-bar">
                    <input type="text" class="search-text" placeholder="Search Document">
                    <div class="icon"><i class="bi bi-search"></i></div>
                </div>
                <div class="memorandum-option">
                    <div class="search">
                        <select class="option-text">
                            <option value="" disabled selected>Select Category</option>
                            <option value="doc1">Memorandum</option>
                            <option value="doc2">Administrative Orders</option>
                            <option value="doc3">Monthly Report Service Personnel</option>
                            <option value="doc4">Claim Monitoring Sheet</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="dashboard-section">
            <div class="dashboard-container">
                <div class="documents">
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
                                            <a href="admin_view.html">View</a>
                                            <a href="#">Download</a>
                                            <a href="admin_edit.html">Edit</a>
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
                                                <a href="admin_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="admin_edit.html">Edit</a>
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
                                                <a href="admin_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="admin_edit.html">Edit</a>
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
                                                <a href="admin_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="admin_edit.html">Edit</a>
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
                                                <a href="admin_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="admin_edit.html">Edit</a>
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
                                                <a href="admin_view.html">View</a>
                                                <a href="#">Download</a>
                                                <a href="admin_edit.html">Edit</a>
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
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/all_docs.js') }}"></script>
@endsection

</body>
</html>