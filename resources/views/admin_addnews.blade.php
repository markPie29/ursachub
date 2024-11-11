<head>
    <!-- Load jQuery (first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Optional: Bootstrap for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
</head>

<body class="admin-news-page">
    @extends('layouts.admin_layout')

    @section('content')
        <div class="admin-news-container">
            <h3>Publish News</h3>
            
            @if($errors->any())
                <div class="admin-news-alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/admin/addnews" method="POST" enctype="multipart/form-data" class="admin-news-form">
                @csrf
                <div class="form-group">
                    <label for="org" class="form-label">Organization:</label>
                    <input type="text" name="org" id="org" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="headline" class="form-label">News / Headline :</label>
                    <input type="text" name="headline" id="headline" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content:</label>
                    <textarea name="content" id="content" class="form-input" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="photos" class="form-label">Photos (min 1, max 5):</label>
                    <input type="file" name="photos[]" id="photos" class="form-input" required multiple accept="image/*">
                </div>
                
                <button type="submit" class="form-submit-button">Publish News</button>
            </form>
        </div>
        
        <!-- Initialize Summernote -->
        <style>
            /* Stronger reset for list styles */
            .note-editable ul, .note-editable ol {
                list-style: initial !important; /* Force list-style to initial */
                margin-left: 20px !important;   /* Ensures bullets/numbers show up within the text area */
                padding-left: 20px !important;
            }
            /* Ensure underline styling in Summernote editor */
            .note-editable u {
                text-decoration: underline !important;
            }
        </style>
        <script>
            $(document).ready(function() {
                $('#content').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['help']]
                    ]
                });

                // Custom JavaScript to ensure list styles are applied correctly
                function enforceListStyles() {
                    // Add bullet points to unordered lists
                    $('.note-editable ul').css({
                        'list-style-type': 'disc',
                        'list-style-position': 'inside'
                    });

                    // Add numbers to ordered lists
                    $('.note-editable ol').css({
                        'list-style-type': 'decimal',
                        'list-style-position': 'inside'
                    });
                }

                // Apply list styles on initial load
                enforceListStyles();

                // Reapply list styles whenever the editor content changes
                $('#content').on('summernote.change', function() {
                    enforceListStyles();
                });
            });
        </script>
    @endsection
</body>
