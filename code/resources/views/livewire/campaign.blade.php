
<div class="container mt-4">
    <div class="mb-4">
        <button class="btn btn-primary" onclick="window.open('/api/v1/campaign/export')">
            Export to CSV
        </button>
        <br>
        <button class="btn btn-info btn-sm">Create a campaign</button>
        <input type="text" id="create_campaign_input" placeholder="type a campaign name">
        <br>
        <br>
        <br>
        <input type="text" 
               wire:model="search" 
               placeholder="Search by campaign..."
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
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Campaign Name
                    @if ($sortField == 'name')
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
                <th>comments</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $campaign)
                <tr>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->booked_amount }}</td>
                    <td>{{ $campaign->actual_amount }}</td>
                    <td>{{ $campaign->adjustments }}</td>
                    <td>{{ $campaign->invoice_amount }}</td>
                    <td><a  href="#" class="show-comments" data-comments="{{ $campaign->comments }}">show comments</a></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="window.location.href='/campaign/detail/{{ $campaign->id }}'">Detail</button>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="window.location='/campaign/comment/{{ $campaign->id}}'">Modify Comments</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

   
    <div class="d-flex justify-content-center mt-3">
        {{ $campaigns->links() }}
    </div>
    <div class="comment-tooltip"></div>
</div>
<style>
    .comment-tooltip {
        position: absolute;
        background: #333;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        display: none;
        max-width: 300px;
        white-space: pre-wrap;
        z-index: 1000;
    }
</style>
<script>
    $(document).ready(function() {
        $(".btn-info").click(function(e) {
            e.preventDefault();
            var campaignName = $("#create_campaign_input").val(); 
            if (campaignName.trim() === "") {
                alert("Campaign name cannot be empty!");
                return;
            }
            $.ajax({
                url: "/api/v1/campaign",
                type: "POST",
                data: {
                    name: campaignName
                },
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
<script>
document.querySelectorAll('.show-comments').forEach(item => {
    item.addEventListener('mouseenter', function(event) {
        let tooltip = document.querySelector('.comment-tooltip');
        tooltip.textContent = this.getAttribute('data-comments');
        tooltip.style.display = 'block';
        tooltip.style.left = event.pageX + 'px';
        tooltip.style.top = (event.pageY + 10) + 'px';
    });

    item.addEventListener('mouseleave', function() {
        document.querySelector('.comment-tooltip').style.display = 'none';
    });
});
</script>