function localstr() {
    localStorage.setItem(1, 'anele');
    localStorage.setItem(2, 'anele');
    localStorage.setItem(3, 'anele');
    console.log(localStorage.length);
    for (var key in localStorage) {
        console.log(key);
    }
}