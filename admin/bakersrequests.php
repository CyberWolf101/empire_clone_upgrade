<?php
include "header.php";
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bakers Requests</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Home</a>
        </li>
        <li class="breadcrumb-item active">
            Bakers Requests
        </li>
    </ol>
</div>

<div class="card shadow mb-4">

    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Request Tracking
        </h6>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="thead-light">
                    <tr>
                        <th>Request Code</th>
                        <th>Guide</th>
                        <th>Requested By</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $sql = mysqli_query($con,"
                        SELECT
                            br.*,
                            bg.item AS guide_name
                        FROM bakers_requests br
                        LEFT JOIN bakers_guide bg
                            ON bg.guide_id = br.guide_id
                        ORDER BY br.id DESC
                    ");

                    while($row = mysqli_fetch_assoc($sql))
                    {
                    ?>

                    <tr>

                        <td>
                            <?= $row['request_code']; ?>
                        </td>

                        <td>
                            <?= $row['guide_name']; ?>
                        </td>

                        <td>
                            <?= $row['requested_by']; ?>
                        </td>

                        <td>
                            <?= date(
                                "d M Y h:i A",
                                strtotime($row['requested_on'])
                            ); ?>
                        </td>

                        <td>

                            <?php
                            if($row['status']=="Collected")
                            {
                                echo '<span class="badge badge-success">Collected</span>';
                            }
                            elseif($row['status']=="Partially Collected")
                            {
                                echo '<span class="badge badge-info">Partially Collected</span>';
                            }
                            else
                            {
                                echo '<span class="badge badge-warning">Pending</span>';
                            }
                            ?>

                        </td>

                        <td>

                            <a href="viewrequest.php?id=<?= $row['id']; ?>"
                               class="btn btn-sm btn-primary">
                                View
                            </a>

                        </td>

                    </tr>

                    <?php
                    }
                    ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
```
