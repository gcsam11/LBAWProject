document.addEventListener('DOMContentLoaded', function() {


    // Function to handle sorting and time sorting changes
    var sortElem = document.getElementById('sort_by');
    var timeSortElem = document.getElementById('time_sort');

    // Function to handle filter form submission
    var filtersForm = document.querySelector('.filters-dropdown form');

    //get current page
    var currentUrl = window.location.href;
    var isUserNewsPage = currentUrl.includes('/user_news');

    if (sortElem && timeSortElem) {
        sortElem.addEventListener('change', function() {
            var formData = new FormData();
            var selectedSort = this.value;
            var selectedTimeSort = timeSortElem.value;
    
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            var token = csrfToken ? csrfToken.getAttribute('content') : '';
    
            if (filtersForm) {
                var formElements = filtersForm.elements;
                for (var i = 0; i < formElements.length; i++) {
                    var element = formElements[i];
                    if (element.name && element.value) {
                        formData.append(element.name, element.value);
                    }
                }
            }
    
            formData.append('_token', token);
            formData.append('sort', selectedSort);
            formData.append('time_sort', selectedTimeSort);
            applyFilters(formData);
        });
    
        timeSortElem.addEventListener('change', function() {
            var formData = new FormData();
            var selectedSort = sortElem.value;
            var selectedTimeSort = this.value;
    
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            var token = csrfToken ? csrfToken.getAttribute('content') : '';
    
            if (filtersForm) {
                var formElements = filtersForm.elements;
                for (var i = 0; i < formElements.length; i++) {
                    var element = formElements[i];
                    if (element.name && element.value) {
                        formData.append(element.name, element.value);
                    }
                }
            }
    
            formData.append('_token', token);
            formData.append('sort', selectedSort);
            formData.append('time_sort', selectedTimeSort);
            applyFilters(formData);
        });
    }


    if (filtersForm) {
        filtersForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('comunismo/n');
            var formDataFilters = new FormData();
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            var token = csrfToken ? csrfToken.getAttribute('content') : '';

            var formElements = filtersForm.elements;
            for (var i = 0; i < formElements.length; i++) {
                var element = formElements[i];
                if (element.name && element.value) {
                    formDataFilters.append(element.name, element.value);
                }
            }
            formDataFilters.append('_token', token);
            formDataFilters.append('sort', sortElem.value);
            formDataFilters.append('time_sort', timeSortElem.value);
            applyFilters(formDataFilters);
        });
    }

    function applyFilters(requestData) {
        if (isUserNewsPage) {
            requestData.append('user_id', window.userID);

        }
        fetch(filterPostsApplyRoute, {
            method: 'POST',
            body: requestData
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                var newsContainer = document.querySelector('.news');
                if (newsContainer) {
                    newsContainer.innerHTML = data.html;
                }
            } else {
                // Handle errors if necessary
            }
        })
        .catch(function(error) {
            console.error('Fetch Error:', error);
        });
    }
    var filterButton = document.getElementById('filter_button');
    var filters = document.getElementById('filters');

    if (filterButton && filters) {
        filterButton.addEventListener('click', function() {
            filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
            var buttonText = this.textContent;
            this.textContent = buttonText === 'Show Filters' ? 'Hide Filters' : 'Show Filters';
        });
    }


});