<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Dream Fight - UFC MMA</title>
@vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen">

<div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-extrabold text-yellow-400 mb-8">Edit Dream Fight</h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 mb-6 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-900/50 p-6 rounded-2xl shadow-2xl backdrop-blur-sm">
        <form method="POST" action="{{ route('dreamfights.update', $dreamfight->id) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Fighter One -->
            <div>
                <label for="fighter-one" class="block text-white font-medium mb-2">Fighter One</label>
                <input type="text" id="search-fighter-one" placeholder="Search fighter..." class="w-full p-2 mb-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                <select name="fighter_one_name" id="fighter-one" required size="5" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}" data-weight="{{ $fighter['WeightClass'] }}"
                            @if($dreamfight->fighter_one_name === $fighter['FirstName'].' '.$fighter['LastName']) selected @endif>
                            {{ $fighter['FirstName'] }} {{ $fighter['LastName'] }} @if(!empty($fighter['Nickname'])) ({{ $fighter['Nickname'] }}) @endif
                        </option>
                    @endforeach
                </select>
                <button type="button" id="clear-fighter-one" class="mt-2 px-4 py-2 bg-red-600 hover:bg-red-500 rounded text-white">Clear</button>
            </div>

            <!-- Fighter Two -->
            <div>
                <label for="fighter-two" class="block text-white font-medium mb-2">Fighter Two</label>
                <input type="text" id="search-fighter-two" placeholder="Search fighter..." class="w-full p-2 mb-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                <select name="fighter_two_name" id="fighter-two" required size="5" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-yellow-400">
                    @foreach($fighters as $fighter)
                        <option value="{{ $fighter['FirstName'] }} {{ $fighter['LastName'] }}" data-weight="{{ $fighter['WeightClass'] }}"
                            @if($dreamfight->fighter_two_name === $fighter['FirstName'].' '.$fighter['LastName']) selected @endif>
                            {{ $fighter['FirstName'] }} {{ $fighter['LastName'] }} @if(!empty($fighter['Nickname'])) ({{ $fighter['Nickname'] }}) @endif
                        </option>
                    @endforeach
                </select>
                <button type="button" id="clear-fighter-two" class="mt-2 px-4 py-2 bg-red-600 hover:bg-red-500 rounded text-white">Clear</button>
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-black font-bold px-6 py-3 rounded hover:bg-yellow-300 shadow hover:shadow-lg transition">
                Update Dream Fight
            </button>
        </form>
    </div>
</div>

<script>
function filterSelect(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);

    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();
        Array.from(select.options).forEach(option => {
            option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
        });

        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].style.display !== 'none') {
                select.selectedIndex = i;
                break;
            }
        }
    });
}

function restrictFighterTwo() {
    const fighterOne = document.getElementById('fighter-one');
    const fighterTwo = document.getElementById('fighter-two');

    const weightOrder = [
        "Atomweight", "Strawweight", "Flyweight", "Bantamweight", "Featherweight",
        "Lightweight", "Welterweight", "Middleweight", "Light Heavyweight", "Heavyweight",
        "Women’s Flyweight", "Women’s Bantamweight", "Women’s Featherweight", "Women’s Lightweight"
    ];

    fighterOne.addEventListener('change', function() {
        if (!fighterOne.selectedOptions[0]) return;
        const selectedWeight = fighterOne.selectedOptions[0].dataset.weight;
        const selectedIndex = weightOrder.findIndex(w => selectedWeight.includes(w));

        Array.from(fighterTwo.options).forEach(option => {
            const optionIndex = weightOrder.findIndex(w => option.dataset.weight.includes(w));
            option.style.display = (optionIndex >= selectedIndex - 1 && optionIndex <= selectedIndex + 1) ? '' : 'none';
        });

        for (let i = 0; i < fighterTwo.options.length; i++) {
            if (fighterTwo.options[i].style.display !== 'none') {
                fighterTwo.selectedIndex = i;
                break;
            }
        }
    });
}

document.getElementById('clear-fighter-one').addEventListener('click', () => document.getElementById('fighter-one').selectedIndex = -1);
document.getElementById('clear-fighter-two').addEventListener('click', () => document.getElementById('fighter-two').selectedIndex = -1);

filterSelect('search-fighter-one', 'fighter-one');
filterSelect('search-fighter-two', 'fighter-two');
restrictFighterTwo();
</script>

</body>
</html>
