const message = document.querySelector('#brilliance');

let notStyled = true;

function time(ms) {
    return new Promise((resolve, reject) => {
        if (notStyled) {
            setTimeout(resolve, ms); // Relationship between work and time
        } else {
            reject(console.log("Already styled"));
        }
    });
}

const charArray = ['B', 'r', 'i', 'l', 'l', 'i', 'a', 'n', 'c', 'e'];

async function textStyler() {
    try {
        for (let i = 0; i < charArray.length; i++) {
            await time(200);
            message.textContent += charArray[i];
        }
    } 
    
    catch(error) {
        console.log(error);
    } 
    
    finally {
        notStyled = false;
        console.log("Text Styled");
    }
}

textStyler();