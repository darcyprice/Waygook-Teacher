<?php
include("includes/header.php");

/* SEARCH BAR */
/*
The below operates the code for the search-teacher-form
Essentially, everytime that User submits the form, I update the sql query
and refresh the page with the new query
*/
// TODO: I suspect that this is a very inefficient way of doing this
if (isset($_POST['search-teacher-button'])) {
    // TODO: consider whether to move this into a class
    // BUG: if search returns 0 results, page doesn't load. Instead, load <div class='no-results-found'>
    // get the values from the form
    // BUG: these values are being passed into sql directly directy (without going through PDO)
    $by_nation = $_POST['by_nationality'];
    $by_gender = $_POST['by_gender'];
    $by_education_level = $_POST['by_education_level'];

    $sql = "SELECT * FROM Users WHERE role = ?";
    $conditions = array();
    // check if the User included 'all' for any of the form values
    if ($by_nation !== 'all') {
        // BUG: this line could cause issues -- it's working, but I deviated from StackOverflow Answer
        $conditions[] = " AND nationality='$by_nation'";
    }
    if ($by_gender !== 'all') {
        $conditions[] = " AND gender='$by_gender'";
    }
    if ($by_education_level !== 'all') {
        $conditions[] = " AND education_level='$by_education_level'";
    }
    // if the User searched for anything other than 'all' for any of the values
    // include those vales in a WHERE clause in $sql
    if (count($conditions) > 0) {
        $sql .= " " . implode($conditions);
        /// $sql .= " ORDER BY userID";
    }
    $sql .= " ORDER BY userID";
} else {
    // default search query (unless amended by search-teacher-form)
    // select 30 random Teachers to display on page
    $sql = "SELECT * FROM Users
            WHERE role = ?
            ORDER BY userID";
}
// run the $sql query (derived from above)
$stmt = $db->run($sql, ['teacher']);
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// unset session variables for search criteria
unset($_SESSION['$by_nation']);
unset($_SESSION['$by_gender']);
unset($_SESSION['$by_education_level']);

/* PAGINATION */
if ((isset($_GET['page_num']))) {
    $page_num = $_GET['page_num'];
} else {
    $page_num = 1;
}
// calculate number of rows in data
$num_rows = count($teachers);
// number of rows per page
$rows_per_page = 12;
// calculate the last page number
$last_page = ceil($num_rows/$rows_per_page);
// ensure the page number isn't less than 1, or more than max pages
if ($page_num < 1) {
    $page_num = 1;
} elseif ($page_num > $last_page) {
    $page_num = $last_page;
}

// LIMIT sql query to limit results to amount for that page
$limit = ' LIMIT ' . ($page_num - 1) * $rows_per_page . ', ' . $rows_per_page;
// add the limit to the (original) sql query
$sql .= $limit;
// run the $sql query (derived from above)
$stmt = $db->run($sql, ['teacher']);
$data_for_this_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="search-container">
    <div id="search-bar">
        <form class="search-teacher-form" method="post">
            <table>
                <tr>
                    <td>
                        <label>Nationality</label>
                        <select id="by_nationality" name="by_nationality" type="text">
                            <option value="all">All</option>
                            <option value="american">American</option>
                            <option value="australian">Australian</option>
                            <option value="british">British</option>
                            <option value="canadian">Canadian</option>
                            <option value="filipino">Filipino</option>
                        </select>
                    </td>
                    <td>
                        <label>Gender</label>
                        <select id="by_gender" name="by_gender" type="text">
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </td>
                    <td>
                        <label>Education Level</label>
                        <select id="by_education_level" name="by_education_level" type="text">
                            <option value="all">All</option>
                            <option value="teritary">Teritary</option>
                            <option value="diploma">Diploma</option>
                            <option value="bachelor">Bachelor</option>
                            <option value="masters">Masters</option>
                            <option value="doctorate">Doctorate</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit" name="search-teacher-button">SEARCH</button>
        </form>
    </div>
    <div id="search-results-container">
        <ul class="search-result-list">
            <?php
            foreach ($data_for_this_page as $row) {
                // create html div each time loops through $query
                echo "<div id='search-view-item'>
                        <span id='search-result'>
                            <a href='profile.php?userID=" . $row['userID'] . "'>
                                <div class='search-result-info photo'>
            						<img src='" . $row['profile_pic'] . "'>
                                </div>
                                <div class='search-result-info'>
                                    <div class='info-name'>
                                        " . $row['first_name'] . "
                                    </div>
                                    <div class='info-details'>
                                        <span class='info-country'>
                                            <img src='" . $row['flag'] . "'>
                                        </span>
                                        <span class='info-rate'>
                                            " . "$18" . "
                                        </span>
                                        <span class='info-reviews'>
                                                " . "3.5 stars" . "
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </span>
                    </div>";
            }
            ?>
        </ul>
        <?php
        /* PAGE LINKS */
        // create the variables
        $previous = $page_num - 1;
        $next = $page_num + 1;
        // show User what page their on, and the total number of pages
        echo "<div class='page-links'>
                <div class='page-links-button'>
                    <a class='button' href='search.php'> << </a>
                    <a class='button' href='search.php?page_num=$previous'> < </a>
                </div>
                <div class='page-links-info'>
                    " . $page_num . " of " . $last_page . " " . "
                </div>
                <div>
                    <a class='button' href='search.php?page_num=$next'> > </a>
                    <a class='button' href='search.php?page_num=$last_page'> >> </a>
                </div>
            </div>";
        ?>
    </div>
</div>

<?php
include("includes/footer.php");
?>
