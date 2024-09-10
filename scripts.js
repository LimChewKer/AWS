document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('login-form');
  const signupForm = document.getElementById('signup-form');
  const showSignup = document.getElementById('show-signup');
  const showLogin = document.getElementById('show-login');
  const dashboard = document.getElementById('dashboard');
  const authContainer = document.getElementById('auth-container');
  const signupContainer = document.getElementById('signup-container');
  const addItemBtn = document.getElementById('add-item-btn');
  const itemModal = document.getElementById('item-modal');
  const itemForm = document.getElementById('item-form');
  const itemCancelBtn = document.getElementById('item-cancel-btn');
  const itemsList = document.getElementById('items-list');

  let loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
  let currentItemId = null;

  showSignup.addEventListener('click', () => {
    authContainer.style.display = 'none';
    signupContainer.style.display = 'block';
  });

  showLogin.addEventListener('click', () => {
    signupContainer.style.display = 'none';
    authContainer.style.display = 'block';
  });

  signupForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const email = document.getElementById('signup-email').value;
    const password = document.getElementById('signup-password').value;

    const users = JSON.parse(localStorage.getItem('users')) || [];
    users.push({ email, password });
    localStorage.setItem('users', JSON.stringify(users));

    alert('Signup successful! You can now log in.');
    signupContainer.style.display = 'none';
    authContainer.style.display = 'block';
  });

  loginForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const email = document.getElementById('login-email').value;
      const password = document.getElementById('login-password').value;

      const storedUsers = JSON.parse(localStorage.getItem('users')) || [];
      const user = storedUsers.find(user => user.email === email && user.password === password);

      if (user) {
          localStorage.setItem('loggedInUser', JSON.stringify(user));
          authContainer.style.display = 'none';
          dashboard.style.display = 'block';
          loadItems();
      } else {
          alert('Invalid credentials');
      }
  });

  addItemBtn.addEventListener('click', () => {
    currentItemId = null;
    itemForm.reset();
    itemModal.style.display = 'block';
  });

  itemCancelBtn.addEventListener('click', () => {
    itemModal.style.display = 'none';
    currentItemId = null;
    itemForm.reset();
  });

  itemForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const itemName = document.getElementById('item-name').value;
    const itemQuantity = document.getElementById('item-quantity').value;
    const itemDescription = document.getElementById('item-description').value;

    const items = JSON.parse(localStorage.getItem('items')) || [];

    if (currentItemId) {
      const itemIndex = items.findIndex(item => item.id === currentItemId);
      items[itemIndex] = { id: currentItemId, name: itemName, quantity: itemQuantity, description: itemDescription };
    } else {
      const newItem = {
        id: 'item-' + new Date().getTime(),
        name: itemName,
        quantity: itemQuantity,
        description: itemDescription
      };
      items.push(newItem);
    }

    localStorage.setItem('items', JSON.stringify(items));
    loadItems();
    itemModal.style.display = 'none';
  });

  // Function to load items and add both edit and delete buttons
  function loadItems() {
    itemsList.innerHTML = '';
    const items = JSON.parse(localStorage.getItem('items')) || [];
    items.forEach(item => {
      const newItem = document.createElement('li');
      newItem.id = item.id;
      newItem.innerHTML = `
        <span class="item-name">${item.name}</span> 
        (<span class="item-quantity">${item.quantity}</span>)
        - <span class="item-description">${item.description}</span>
        <button class="edit-item-btn">Edit</button>
        <button class="delete-item-btn">Delete</button>
      `;
      itemsList.appendChild(newItem);

      // Edit button event listener
      newItem.querySelector('.edit-item-btn').addEventListener('click', () => {
        currentItemId = item.id;
        document.getElementById('item-name').value = item.name;
        document.getElementById('item-quantity').value = item.quantity;
        document.getElementById('item-description').value = item.description;
        itemModal.style.display = 'block';
      });

      // Delete button event listener
      newItem.querySelector('.delete-item-btn').addEventListener('click', () => {
        deleteItem(item.id);
      });
    });
  }

  // Delete item function
  function deleteItem(itemId) {
    if (confirm("Are you sure you want to delete this item?")) {
      let items = JSON.parse(localStorage.getItem('items')) || [];
      items = items.filter(item => item.id !== itemId);
      localStorage.setItem('items', JSON.stringify(items));
      loadItems();
      alert("Item deleted successfully.");
    }
  }

  updateUI(); // Ensure the initial UI is set correctly
});
