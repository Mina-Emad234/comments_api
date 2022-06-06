<?php
$page_title = "Comments";
require "partials/header.php";
if(makeSession($total_page)){
    echo "<div class='alert alert-danger'>{$_SESSION['not_found']}</div>";
}else{
?>
    <a href="../api/logout" onclick="return confirm('Are you sure want to logout?')">Logout</a>


    <div style="padding: 0 15px;">
    <div  class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="print">
            <tr>
                <th class="text-center">NO</th>
                <th>NAME</th>
                <th>Email</th>
                <th>COMMENT</th>
            </tr>
            <?php
                foreach ($comments->make_pagination() as $comment){

 ?>
             <tr <?= ($no % 2 == 0) ? "class='warning' " : ""?>>
                            <td class="align-middle text-center"> <?php echo $no; ?> </td>
                            <td class="align-middle"> <?php echo $comment['name']; ?> </td>
                            <td class="align-middle"> <?php echo $comment['email']; ?> </td>
                            <td class="align-middle"> <?php echo $comment['body']; ?> </td>
                        </tr>
                        <?php
                        $no++; // Add 1 for each looping
                    }
               ?>
                </table>

    <div>


     <ul class="pagination">
            <!-- LINK FIRST AND PREV -->
            <?php
            if (page() == 1) {// If the page is used to 1, then disable the PREV link
            ?>
                <li class="disabled"><a href="#">First</a></li>
                <li class="disabled"><a href="#">&laquo;</a></li>
            <?php
            } else { // If you opened page

            ?>
                <li><a href="1">First</a></li>
                <li><a href="<?php echo $link_prev; ?>">&laquo;</a></li>
            <?php
            }
            ?>

            <?php
            // Create a query to count all amounts of data
            // To the end of the link number if page number not before the end page by 3 pages
            //(5<(12-3))?5+3:3; =3
            // 5 < (12ئ - 3)) ? 5 + 3 : 3 ; = 3
            for ($i = $start_number; $i <= $end_number; $i++) {
                $link_active = (page() == $i) ? 'class="active"' : '';
            ?>
                <li <?php echo $link_active; ?>><a href="<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php } ?>

            <!-- LINK NEXT AND LAST -->
            <?php
            // If the page is equal to the number of pages, then disable the NEXT link
             // This means that the page is the last page
             if (page() == $total_page) {// if the last page
            ?>
                <li class="disabled"><a href="#">&raquo;</a></li>
                <li class="disabled"><a href="#">Last</a></li>
            <?php
            } else { // If not the last page
                $link_next = (page() < $total_page) ? page() + 1 : $total_page;
            ?>
                <li><a href="<?php echo $link_next; ?>">&raquo;</a></li>
                <li><a href="<?php echo $total_page; ?>">Last</a></li>
            <?php
            }
            ?>
        </ul>
    </div>

<?php
}
require "partials/footer.php";
