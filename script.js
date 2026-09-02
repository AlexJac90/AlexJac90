// script.js
document.getElementById('item-form').addEventListener('submit', addItem);

// Load any items already stored in the database when the page opens.
document.addEventListener('DOMContentLoaded', loadItems);

function loadItems() {
    fetch('get_items.php')
        .then(response => response.json())
        .then(items => {
            document.getElementById('shopping-list').innerHTML = '';
            items.forEach(item => renderItem(item.item_name, item.quantity, item.purchased));
        })
        .catch(error => console.error('Error loading items:', error));
}

function renderItem(itemName, quantity, purchased) {
    const li = document.createElement('li');
    if (purchased) {
        li.classList.add('completed');
    }

    const label = document.createElement('span');
    label.textContent = `${itemName} (x${quantity})`;
    li.appendChild(label);

    // Create a "mark as purchased" button
    const completeButton = document.createElement('button');
    completeButton.textContent = 'Complete';
    completeButton.classList.add('complete');
    completeButton.onclick = function () {
        li.classList.toggle('completed');
        updateItemInDB(itemName, li.classList.contains('completed'));
    };
    li.appendChild(completeButton);

    // Create a "delete" button
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.classList.add('delete');
    deleteButton.onclick = function () {
        li.remove();
        deleteItemFromDB(itemName);
    };
    li.appendChild(deleteButton);

    // Append to the list
    document.getElementById('shopping-list').appendChild(li);
}

function addItem(event) {
    event.preventDefault();

    const itemName = document.getElementById('item-input').value.trim();
    const quantity = document.getElementById('quantity-input').value;

    if (itemName === "" || quantity <= 0) {
        return;
    }

    renderItem(itemName, quantity, false);

    // Add item to the database
    addItemToDB(itemName, quantity);

    // Clear the input fields
    document.getElementById('item-input').value = '';
    document.getElementById('quantity-input').value = '';
}

function addItemToDB(itemName, quantity) {
    fetch('add_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item_name: itemName, quantity: quantity, purchased: false })
    })
    .then(response => response.json())
    .then(data => console.log('Item added:', data))
    .catch(error => console.error('Error adding item:', error));
}

function updateItemInDB(itemName, purchased) {
    fetch('update_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item_name: itemName, purchased })
    })
    .then(response => response.json())
    .then(data => console.log('Item updated:', data))
    .catch(error => console.error('Error updating item:', error));
}

function deleteItemFromDB(itemName) {
    fetch('delete_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item_name: itemName })
    })
    .then(response => response.json())
    .then(data => console.log('Item deleted:', data))
    .catch(error => console.error('Error deleting item:', error));
}
