<!--  ACCOUNTANT  -->
<?php if ($status == "accountant") { ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEx" aria-expanded="true"
            aria-controls="collapseBootstrap">
            <i class="fas fa-money-bill"></i>
            <span>Expenses</span>
        </a>
        <div id="collapseEx" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="expenses.php">View expenses</a>
                <a class="collapse-item" href="expense_title.php">Expense titles</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="itemsreport.php">
            <i class="fas fa-fw fa-tools"></i>
            <span>Stocks Report</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="inventory_log_details.php">
            <i class="fas fa-fw fa-tools"></i>
            <span>Inventory log</span>
        </a>
    </li>
    <!-- <li class="nav-item">
        <a class="nav-link" href="foodsalesreport.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Sales Report</span>
        </a>
    </li> -->
<?php } ?>



<!--  CASHIER  -->
<?php if ($status == "cashier") { ?>
   
    <li class="nav-item">
        <a class="nav-link" href="pending_event_orders.php">
            <i class="fas fa-fw fa-tools"></i>
            <span>Event Orders</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="viewpreorders.php">
            <i class="fas fa-fw fa-tools"></i>
            <span>Pre-Orders</span>
        </a>
    </li>

<?php } ?>