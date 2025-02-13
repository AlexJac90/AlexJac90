// script.js
document.getElementById('item-form').addEventListener('submit', addItem);

function addItem(event) {
    event.preventDefault();

    const itemName = document.getElementById('item-input').value.trim();
    const quantity = document.getElementById('quantity-input').value;

    if (itemName === "" || quantity <= 0) {
        return;
    }

    // Create a new item element
    const li = document.createElement('li');
    li.textContent = `${itemName} (x${quantity})`;

    // Create a "mark as purchased" button
    const completeButton = document.createElement('button');
    completeButton.textContent = 'Complete';
    completeButton.classList.add('complete');
    completeButton.onclick = function () {
        li.classList.toggle('completed');
        updateItemInDB(itemName, true);
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
