@props(['bids'])

<table class="w-full text-left table-auto min-w-max">
    <thead>
        <tr>
            <th class="p-4 border-b border-blue-accent bg-blue-secondary">
                <p class="block font-sans text-sm antialiased leading-none text-white">
                    User
                </p>
            </th>
            <th class="p-4 border-b border-blue-accent bg-blue-secondary">
                <p class="block font-sans text-sm antialiased leading-none text-white">
                    Amount
                </p>
            </th>
            <th class="p-4 border-b border-blue-accent bg-blue-secondary">
                <p class="block font-sans text-sm antialiased leading-none text-white">
                    Date
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bids as $bid)
        <tr class="{{ $loop->first ? 'top-bid' : 'even:bg-blue-secondary' }}" data-bid-amount="{{ $bid->current_amount }}">
            <td class="p-4">
                <p
                    class="block font-sans text-sm antialiased font-normal leading-normal text-gray-400">
                    {{ $bid->user->username }}
                </p>
            </td>
            <td class="p-4">
                <p
                    class="block font-sans text-sm antialiased font-normal leading-normal text-gray-400">
                    <x-money amount="{{ $bid->current_amount }}" currency="GBP" />
                </p>
            </td>
            <td class="p-4">
                <p
                    class="block font-sans text-sm antialiased font-normal leading-normal text-gray-400">
                    {{ $bid->updated_at->format('d/m H:i:s') }}
                </p>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
