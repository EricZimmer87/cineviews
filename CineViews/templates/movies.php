<?php $this->layout('master', ['title' => 'Movies & Shows']) ?>
<h1 class="text-center"><i class="bi bi-film"></i> Movies and TV Shows <i class="bi bi-film"></i></h1>
<?php session_start() ?>

<!-- Sorting and Filtering Displays -->
<div class="container">
    <div class="row">
        <div class="col">
            <label for="sortSelect" class="form-label"><strong>Sort:</strong></label>
            <select id="sortSelect" class="form-select">
                <option value="title-asc">Title (A-Z)</option>
                <option value="title-desc">Title (Z-A)</option>
                <option value="date-asc">Release Date (Oldest First)</option>
                <option value="date-desc">Release Date (Newest First)</option>
            </select>
        </div>

        <div class="col">
            <label for="genreSelect" class="form-label"><strong>Filter by Genre:</strong></label>
            <select id="genreSelect" class="form-select">
                <option value="">All Genres</option>
                <?php
                // Get genres from database
                $genres = GetGenres();
                // Loop through genres to display in dropdown
                foreach ($genres as $genre) {
                    echo '<option value="' . $genre['GenreName'] . '">' . $genre['GenreName'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<!--
    I initially had the movies displayed with PHP, using Bootstrap's card component.
For sorting and filtering, I converted it to use JavaScript, dynamically creating the
HTML elements and adding Bootstrap's classes and styles.  I wanted to use JavaScript
for sorting and filtering, for client-side processing, so the page wouldn't have to
reload every time, and there would be an increase in performance.  If the database
contained 1,000's of movies, I'd probably go with server-side processing instead.
-->
<div class="container d-flex flex-wrap" id="moviesContainer"></div>

<script>
    var sessionId = <?= isset($_SESSION['id']) ? $_SESSION['id'] : 'null' ?>;

    // Convert $movies PHP to JavaScript variable
    var movies = <?php echo $movies; ?>;

    // Initialize filteredMovies with all movies
    var filteredMovies = movies;

    // Displays the movies
    function renderMovies(movies) {
        var container = document.getElementById("moviesContainer");
        // Ensure content is cleared before displaying movies
        container.innerHTML = "";

        // Check if there are no movies within sort/filter parameters
        if (movies.length === 0) {
            // Display no results found if no movies
            var noResults = document.createElement("p");
            noResults.textContent = "No results found.";
            noResults.className = "mt-5";
            container.appendChild(noResults);
        } else {
            movies.forEach(function(m) {
                // Create the Bootstrap card element
                var card = document.createElement("div");
                card.className = "card m-2";
                card.style.width = "18rem";

                // Add the movie image
                var img = document.createElement("img");
                img.src = m.Art;
                img.className = "card-img-top";
                img.alt = "picture of " + m.MovieTitle;

                // Bootstrap div for rest of movie info in card
                var cardBody = document.createElement("div");
                cardBody.className = "card-body";

                // Add movie title to card
                var title = document.createElement("h5");
                title.className = "card-title";
                title.textContent = m.MovieTitle;

                // Add is series to card
                var type = document.createElement("p");
                type.className = "fs-6";
                type.textContent = m.IsSeries == 1 ? "Series" : "Movie";

                // Add release date to card
                var releaseDate = document.createElement("p");
                releaseDate.className = "card-text";
                // Reformat the date
                releaseDate.textContent = new Date(m.ReleaseDate).toLocaleDateString("en-US", {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Add Bootstrap's card-text class for genre list
                var genre = document.createElement("p");
                genre.className = "card-text";
                genre.textContent = m.Genre;

                // Add Bootstrap's button for movie detail link
                var detailsLink = document.createElement("a");
                detailsLink.href = "<?= $router->generate('movieDetail', ['movieId' => '']) ?>" + m.MovieId;
                detailsLink.className = "btn btn-primary";
                detailsLink.id = "details-link";
                // Create and append the Bootstrap icon
                var icon = document.createElement("i");
                icon.className = "bi bi-card-list";
                detailsLink.appendChild(icon);
                detailsLink.appendChild(document.createTextNode(" Movie Details"));

                // Add all fields to Bootstrap's card body element
                cardBody.appendChild(title);
                cardBody.appendChild(type);
                cardBody.appendChild(releaseDate);
                cardBody.appendChild(genre);
                cardBody.appendChild(detailsLink);

                // Add image and card body to card element
                card.appendChild(img);
                card.appendChild(cardBody);

                // Check if admin is logged in
                if (sessionId == 26) {
                    // Add a div to contain "Edit" and "Delete" buttons on a new line
                    var buttonsDiv = document.createElement("div");
                    buttonsDiv.className = "btn-group-horizontal mt-2";

                    // Add edit button
                    var editButton = document.createElement("a");
                    editButton.className = "btn btn-warning mx-1";
                    editButton.href = "<?= $router->generate('movieUpdateForm', ['movieId' => '']) ?>" + m.MovieId;
                    // Create and append the Bootstrap icon
                    var editIcon = document.createElement("i");
                    editIcon.className = "bi bi-pencil";
                    editButton.appendChild(editIcon);
                    editButton.appendChild(document.createTextNode(" Edit"));

                    // Add delete button
                    var deleteButton = document.createElement("a");
                    deleteButton.className = "btn btn-danger mx-1";
                    deleteButton.href = "<?= $router->generate('movieDeleteConfirm', ['movieId' => '']) ?>" + m.MovieId;
                    // Create and append the Bootstrap icon
                    var deleteIcon = document.createElement("i");
                    deleteIcon.className = "bi bi-trash";
                    deleteButton.appendChild(deleteIcon);
                    deleteButton.appendChild(document.createTextNode(" Delete"));

                    // Append edit and delete buttons to the buttons div
                    buttonsDiv.appendChild(editButton);
                    buttonsDiv.appendChild(deleteButton);

                    // Add buttons div to card body
                    cardBody.appendChild(buttonsDiv);
                }

                // Add everything to the main container element
                container.appendChild(card);
            });
        }
    }

    // Sorting function
    function sortMovies(sortBy) {
        // Function to sort ignoring "the " at beginning of movie title
        const customSort = (a, b) => {
            const titleA = a.MovieTitle.startsWith("The ") ? a.MovieTitle.substring(4) : a.MovieTitle;
            const titleB = b.MovieTitle.startsWith("The ") ? b.MovieTitle.substring(4) : b.MovieTitle;
            return titleA.localeCompare(titleB);
        };

        if (sortBy === "title-asc") {
            filteredMovies.sort(customSort);
        } else if (sortBy === "title-desc") {
            filteredMovies.sort((a, b) => customSort(b, a)); // Reverse order for descending
        } else if (sortBy === "date-asc") {
            filteredMovies.sort((a, b) => new Date(a.ReleaseDate) - new Date(b.ReleaseDate));
        } else if (sortBy === "date-desc") {
            filteredMovies.sort((a, b) => new Date(b.ReleaseDate) - new Date(a.ReleaseDate));
        }
        renderMovies(filteredMovies);
    }


    // Filtering function
    function filterMovies(genre) {
        if (genre) {
            filteredMovies = movies.filter(m => m.Genre.includes(genre));
        } else {
            // If no genre is selected, show all movies
            filteredMovies = movies;
        }
        // Retain sorting option after filtering
        sortMovies(document.getElementById("sortSelect").value);
    }

    // Event listeners for sort and filter
    document.getElementById("sortSelect").addEventListener("change", function() {
        sortMovies(this.value);
    });

    document.getElementById("genreSelect").addEventListener("change", function() {
        filterMovies(this.value);
    });

    // Initial display of movies
    sortMovies("title-asc"); // Display A-Z initially, ignoring "the "
    renderMovies(filteredMovies);
</script>