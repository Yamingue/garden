const token = 'eyJpYXQiOjE2NDI2MTgwODgsImV4cCI6MTY0MjYyMTY4OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoieWFta2luZzAxQGdtYWlsLmNvbSJ9';

fetch("http://garden.test/api/parent/",{
    mode:'no-cors',
    headers:{
        'Authorization':"Bearer "+token,
        'Content-Type': 'application/json',
    }
})
.then(res=>res.json)
.then(data=>data)
.catch(e=>{
    console.log(e)
})
