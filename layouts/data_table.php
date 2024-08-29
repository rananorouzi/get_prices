<div id="table_container">
    <table>
        <tr>
            <th>Numero</th>
            <th>Nimi</th>
            <th>Pullokoko</th>
            <th>Hinta</th>
            <th>PriceGBP</th>
            <th>Last Update</th>
            <th>order amount</th>
            <th>Order</th>
        </tr>
        <?php
        if(!empty($alkoData)){
            foreach($alkoData as $data){
                ?>
                <tr>
                    <td><?php echo $data['numero'];?></td>
                    <td><?php echo $data['nimi'];?></td>
                    <td><?php echo $data['pullokoko'];?></td>
                    <td><?php echo $data['hinta'];?></td>
                    <td><?php echo $data['priceGBP'];?></td>
                    <td><?php echo date('m/d/Y', $data['timestamp']); ?></td>
                    <td id="amount_<?php echo $data['numero'];?>"><?php echo $data['orderamount'];?></td>
                    <td><button onclick="changeOrderAmount(<?php echo $data['numero'];?>,'add')">ADD</button> <button onclick="changeOrderAmount(<?php echo $data['numero'];?>,'clear')">Clear</button></td>
                </tr>
                <?php
            }
        }else{
            ?>
            <tr><td colspan="8">There is No Data To Show!</td> </tr>
            <?php
        }
        ?>
    </table>
    <div id="page">
        <a id="prev" href="#page_<?php echo $page-1; ?>" onclick="callAlkoData(this);return false;">prev</a>
        page <?php echo $page; ?>
        <a id="next" href="#page_<?php echo $page+1; ?>" onclick="callAlkoData(this);return false;">next</a>
    </div>
</div>