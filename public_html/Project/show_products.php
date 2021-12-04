<div class="container-fluid">
    <div class="row row-cols-1 row-cols-md-5 g-4 justify-content-center ">
        <?php foreach ($results as $item) : ?>
            <div class="col">
                <div class="card h-100 bg-light">
                    <div class="card-header fs-4">
                        <?php se($item, "name"); ?>
                    </div>

                    <div class="card-body">

                        <p class="card-text">Description: <br><?php se($item, "description"); ?></p>
                    </div>
                    <div class="card-footer">
                        Cost: $<?php se($item, "unit_price"); ?>
                        <button onclick="purchase('<?php se($item, 'id'); ?>')" class="btn btn-primary">Add To Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>