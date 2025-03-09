
<div class="container mt-4">
    
    <div class="mb-4">
        <button class="btn btn-dark btn-sm" onclick="window.location.href='/'">Back to Campaigns</button>
        <br>
        <br>
        <button class="btn btn-info btn-sm">Create line item</button>
        <input type="text" id="line_item_name" placeholder="type a line item name">
        <input type="text" id="booked_amount" placeholder="type a booked amount">
        <input type="text" id="actual_amount" placeholder="type a actual amount">
        <br>
        <br>
        <br>
        <input type="text" 
               wire:model="search" 
               placeholder="Search by line item..."
               class="px-4 py-2 border border-gray-300 rounded">
        <br>
        <select wire:model="filterField" class="px-4 py-2 border border-gray-300 rounded">
            <option value="booked_amount">Booked Amount</option>
            <option value="actual_amount">Actual Amount</option>
            <option value="adjustments">Adjustments</option>
            <option value="invoice_amount">Invoice Amount</option>
        </select>

     
        <select wire:model="filterOperator" class="px-4 py-2 border border-gray-300 rounded">
            <option value=">=">>=</option>
            <option value="<="><=</option>
        </select>

       
        <input type="number" 
               wire:model="filterValue" 
               placeholder="Enter value"
               class="px-4 py-2 border border-gray-300 rounded">
    </div>
    <table class="table table-hover table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th wire:click="sortBy('line_item_id')" style="cursor: pointer;">
                    ID
                    @if ($sortField == 'line_item_id')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('compaign_name')" style="cursor: pointer;">
                    Campaign Name
                    @if ($sortField == 'compaign_name')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('line_item_name')" style="cursor: pointer;">
                    Line Item Name
                    @if ($sortField == 'line_item_name')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('booked_amount')" style="cursor: pointer;">
                    Booked Amount
                    @if ($sortField == 'booked_amount')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('actual_amount')" style="cursor: pointer;">
                    Actual Amount
                    @if ($sortField == 'actual_amount')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('adjustments')" style="cursor: pointer;">
                    Adjustments
                    @if ($sortField == 'adjustments')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th wire:click="sortBy('invoice_amount')" style="cursor: pointer;">
                    Invoice Amount
                    @if ($sortField == 'invoice_amount')
                        @if ($sortDirection == 'asc')
                            <span>&uarr;</span>
                        @else
                            <span>&darr;</span>
                        @endif
                    @endif
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lineItems as $lineItem)
                <tr>
                    <td>
                        {{ $lineItem->line_item_id }}
                        <input type="hidden" value="{{ $lineItem->line_item_id }}" class="line_item_id" >
                        <input type="hidden" value="{{ $lineItem->campaign_id }}" class="campaign_id" >
                    </td>
                    <td>{{ $lineItem->compaign_name }}</td>
                    <td>{{ $lineItem->line_item_name }}</td>
                    <td>{{ $lineItem->booked_amount }}</td>
                    <td>
                        <div class="actual_amount_div">
                        {{ $lineItem->actual_amount }}
                        </div>
                    </td>
                    <td>
                        <div class="adjust_show_div">
                        {{ $lineItem->adjustments }}
                        </div>
                        <div class="adjust_input_div" style="display: none;">
                        <input type="input" value="{{ $lineItem->adjustments }}" class="adjust_input" >
                        </div>
                    </td>
                    <td>
                        <div class="invoice_amount_div">
                        {{ $lineItem->invoice_amount }}
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" class="adjust_button">Modify Adjustments</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
    <div class="d-flex justify-content-center mt-3">
        {{ $lineItems->links() }}
    </div>
</div>
<script>
$(document).ready(function () {
    $(".btn-primary").click(function () {
        let row = $(this).closest("tr");
        let adjustShowDiv = row.find(".adjust_show_div");
        let adjustInputDiv = row.find(".adjust_input_div");
        let invoiceAmountDiv = row.find(".invoice_amount_div");
        let actualAmountDiv  = row.find(".actual_amount_div");
        let adjustInput = row.find(".adjust_input");
        let lineItemId = row.find(".line_item_id").val();
        let campaignId = row.find(".campaign_id").val();
        let button = $(this);

        if (button.text() === "Modify Adjustments") {
            adjustShowDiv.hide();
            adjustInputDiv.show();
            button.text("Confirm");
        } else {
            let newAdjustments = adjustInput.val();
           
            $.ajax({
                url: "/api/v1/line-item/adjustments",
                type: "PATCH",
                contentType: "application/json",
                data: JSON.stringify({
                    line_item_id: lineItemId,
                    campaign_id: campaignId,
                    adjustments: newAdjustments
                }),
                success: function (response) {
                    adjustShowDiv.text(newAdjustments);
                    invoiceAmountDiv.html( Number(actualAmountDiv.html())+Number(newAdjustments));
                    adjustShowDiv.show();
                    adjustInputDiv.hide();
                    button.text("Modify Adjustments");
                },
                error: function (xhr, status, error) {
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        alert(errorResponse.msg);
                    } catch (e) {
                        alert("An error occurred.");
                    }
                    console.error(xhr.responseText);
                }
            });
        }
    });

    $(".btn-info").click(function(e) {
        let lineItemName = $("#line_item_name").val();
        let bookedAmount = $("#booked_amount").val();
        let actualAmount = $("#actual_amount").val();
        var url = window.location.href; 
        var campaignId = url.match(/(\d+)$/)[0];

        $.ajax({
            url: "/api/v1/line-item/",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                name: lineItemName,
                campaign_id: campaignId,
                booked_amount: bookedAmount,
                actual_amount: actualAmount
            }),
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    alert(errorResponse.msg);
                } catch (e) {
                    alert("An error occurred.");
                }
                console.error(xhr.responseText);
            }
        });
    });
});

</script>