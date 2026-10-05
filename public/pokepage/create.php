<?php
require __DIR__ . '/../../src/require/bootstrap.php';
require __DIR__ . '/../../src/templates/header.php';
?>

<div class="flex flex-col">
    <form action="create.php" method="" class="flex w-64 flex-col gap-2">
        <label for="name">Nom</label>
        <input class="border-2 border-grey-800 rounded-m" type="text" id="name" name="name" />

        <label for="picture">Image</label>
        <input class="border-2 border-grey-800 rounded-m" type="url" id="picture" name="picture" />
        <div class="flex">
            <label for="type-1">Type 1</label>
            <input class="border-2 border-grey-800 rounded-m" type="text" id="type-1" name="type-1" />

            <label for="type-2">Type 2</label>
            <input class="border-2 border-grey-800 rounded-m" type="text" id="type-2" name="type-2" />
        </div>
        <label for="item">Objet</label>
        <input class="border-2 border-grey-800 rounded-m" type="text" id="item" name="item" />
        <div>
            <div class="flex">
                <label for="move-1">Attaque 1</label>
                <input class="border-2 border-grey-800 rounded-m" type="text" id="move-1" name="move-1" />

                <label for="move-2">Attaque 2</label>
                <input class="border-2 border-grey-800 rounded-m" type="text" id="move-2" name="move-2" />
            </div>
            <div class="flex">
                <label for="move-3">Attaque 3</label>
                <input class="border-2 border-grey-800 rounded-m" type="text" id="move-3" name="move-3" />

                <label for="move-4">Attaque 4</label>
                <input class="border-2 border-grey-800 rounded-m" type="text" id="move-4" name="move-4" />
            </div>
        </div>
        <label for="hp">HP</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="hp" name="hp" />

        <label for="atk">Attaque</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="atk" name="atk" />

        <label for="def">Défense</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="def" name="def" />

        <label for="spa">Atq. Spé.</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="spa" name="spa" />

        <label for="spd">Déf. Spé.</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="spd" name="spd" />

        <label for="spe">Vitesse</label>
        <input class="border-2 border-grey-800 rounded-m" type="number" id="spe" name="spe" />

        <label for="nature">Nature</label>
        <input class="border-2 border-grey-800 rounded-m" type="text" id="nature" name="nature" />

        <button class="border-2 border-grey-800 rounded-m" type="submit">Créer</button>
    </form>
</div>

<?php
require __DIR__ . '/../../src/templates/footer.php';
?>

