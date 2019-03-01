<!--
The page shows the list of all lessons for userLoggedIn

On this page, we display:
* the lessons preview for all the teacher-student relationships for the userLoggedIn
* by clicking on one of the lessons preview, a drop down appears to show the details for all the lessons of that teacher-student relationship

Tasks:
1) Display
2) Write SQL query to collect all lessons involving userLoggedIn
3) Display each employment in a 'lesson-list'
    ** Preview includes:
    3.1) Photo of other User
    3.2) Name of other User
    3.3) Prepaid amount for that employment
    3.4) Price per hour for that employment
    3.5) Total number of lessons for that employment
    3.6) Button to send message to other User
    3.7) Button to schedule a lesson with other User
-->

<?php
include("includes/header.php");

// select all employments involving userLoggedIn
$sql = "SELECT * FROM `Employments`
        WHERE teacher_id = ?
        OR student_id = ?";
$stmt = $db->run($sql, [$userLoggedInID, $userLoggedInID]);
$employments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-container">
    <div class="page-heading">
        <h2>Lessons</h2>
    </div>
    <div class="page-content">
        <table class="lessons-table page-table">
            <thead class="lessons-head page-table-head">
                <th>Name</th>
                <th>Prepaid amount</th>
                <th>Price per hour</th>
                <th>Actions</th>
            </thead>
            <?php
            foreach ($employments as $employment_row) {
                // fetch the first_name, last_name of the 'other User' involved in the employment
                // use if/else to get userID of 'the other messanger'
                // if $userLoggedInID != $from_user_id, then $from_user_id must equal $otherUserID, and vice versa
                if ($userLoggedInID == $employment_row['teacher_id']) {
                    $otherUserID = $employment_row['student_id'];
                } else {
                    $otherUserID = $employment_row['teacher_id'];
                }
                // collected from $employment_row['from_user_id'] which has FK relation with User.userID
                $sql = "SELECT * FROM Users WHERE userID = ?";
                $stmt = $db->run($sql, [$otherUserID]);
                $other_user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                // collect all lessons associated with this employment from Lessons table
                $sql = "SELECT * FROM Lessons WHERE employment_id = ?";
                $stmt = $db->run($sql, [$employment_row['employmentID']]);
                $lessons = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<tbody class='lessons-body page-table-body'>
                        <tr class='tr-row'>
                            <td class='td-profile-info'>
                                <div class='info-content profile-pic'>
                                    <img src=" . $other_user_row['profile_pic'] . " alt='profile-pic'>
                                    " . $other_user_row['first_name'] . "
                                </div>
                            </td>
                            <td class='td-prepaid'>
                                " . $employment_row['prepaid_amount'] . "
                            </td>
                            <td class='td-rate'>
                                " . $employment_row['rate'] . "
                            </td>
                            <td class='td-actions'>
                                <ul class='ul-links ul-actions'>
                                    <li><a href='conversation.php'>Send message</a></li>
                                    <li><a href='conversation.php'>Schedule lesson</a></li>
                                    <li><a href='conversation.php'>View profile</a></li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>";
            ?>
        </table>
        <?php




            /*
            // create html div each time loops through $query
            echo "<a href='conversation.php?userID=" . $otherUserID . "'>
                    <div class='conversation-item'>
                        <span id='conversation-result'>
                            <div class='conversation-photo profile-photo'>
                                <img src=" . $other_user_row['profile_pic'] . " alt='from-user-photo'>
                            </div>
                            <div class='conversation-name'>
                                " . $other_user_row['first_name'] . "
                            </div>
                            <div class='conversation-prepaid'>
                                " . $employment_row['prepaid_amount'] . "
                            </div>
                            <div class='conversation-date'>
                                " . $lessons['datetime'] . "
                            </div>
                        </span>
                    </div>
                </a>";
            */
        }
        ?>
    </div>

</div>

<?php
include("includes/footer.php");
?>