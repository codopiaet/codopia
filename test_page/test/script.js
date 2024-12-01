const submitter = document.getElementById("submitter");
const choice = document.querySelectorAll(".choices");
let el = document.querySelectorAll(".container a");
let answer;
let element=null;
const form = document.querySelector("form");
const errorElement = document.getElementById("error");
let wrong=0, right=0;

el.forEach(elt=>{
        elt.addEventListener("click",()=>{
           if (element) {
            document.getElementById(element).classList.remove("selected");
           }
         if (elt.classList.contains("selected")) { 
             
            elt.classList.remove("selected")
        } else{
         elt.classList.add("selected");
         element = elt.id;
        }
        });
});

const showError = (message) => {
    errorElement.style.display= "block";
    errorElement.textContent = message;
}

const checkAnswer = async (answer) => {
    console.log("Check ans");
    let data = new FormData();
    data.append("answer",answer);
    let raw = await fetch("process.php", {
        method: "POST",
        body: data
    })
    let resp = await raw.json();
    return await resp
}

const loadingEl = document.createElement("h4");
loadingEl.textContent = "Loading...";
const showReport = ()=>{
            document.querySelector(".container").style.display= "none";
        
            const reportElement = document.getElementById("main-body");
            reportElement.classList.add("container-rep");
            const wrongEl = document.createElement("h3");
            const rightEl = document.createElement("h3");
            wrongEl.style.color = "#e74a3b";
            rightEl.style.color = "#09c47f";
            wrongEl.textContent = `Wrong: ${wrong} -${wrong*10}`;
            rightEl.textContent  =`Right: ${right} +${right*10}`;
            reportElement.appendChild(wrongEl);
            reportElement.appendChild(rightEl);
        }


const updateDOM = (realAnswer, userAnswer)=>{
    console.log("Update DOM");
    let userAnswerEl= document.getElementById(userAnswer);
    if (submitter.textContent != "Next") submitter.textContent = "Next"; submitter.classList.add("next");


    if (realAnswer.correct === 1){
        right = right + 1;
        if (userAnswerEl.classList.contains("selected")){
            userAnswerEl.classList.replace("selected","correct");
        }
        else {
            userAnswerEl.classList.add("correct");
        }
        if (realAnswer.error === 12){
            document.documentElement.appendChild(loadingEl);
            setTimeout(()=>{
                document.documentElement.removeChild(loadingEl);
                showReport();
            }, 1000);
        }
    }
    else if (realAnswer.correct === 0){
        wrong = wrong + 1;
        
        if (userAnswerEl.classList.contains("selected")){
            userAnswerEl.classList.replace("selected","wrong");
            document.getElementById(realAnswer.ans.answer).classList.add("correct");
        }
        if (realAnswer.error === 12){
            setTimeout(()=>{
                showReport();
            }, 1000);
        }
    }
    
    else {
        alert(realAnswer.error)
    }
    return realAnswer.nextQuestion;
}
let loading=false;

const updateQuestions = (nextQuestion)=>{
    document.querySelector(".correct").classList.remove("correct");
    element = null;
    if (document.querySelector(".wrong")) document.querySelector(".wrong").classList.remove("wrong");
    
    document.getElementById("qn").innerHTML = DOMPurify.sanitize(nextQuestion.question);
    document.getElementById("topic").textContent=nextQuestion.topic;
    document.getElementById("a").textContent = `A: ${nextQuestion.a}`;
    document.getElementById("b").textContent = `B: ${nextQuestion.b}`;
    document.getElementById("c").textContent = `C: ${nextQuestion.c}`;
    document.getElementById("d").textContent = `D: ${nextQuestion.d}`;
    // next button
}


let data,nextQuestion;
const theWholeProcess = async (e)=>{
    e.preventDefault();   
    if (!element) {
        showError("Please enter your answer")
    }
    else {
        data = await checkAnswer(element);
        // Finally fixed
        nextQuestion = updateDOM(data,element);
        document.querySelector(".next").addEventListener("click", ()=>{
            updateQuestions(JSON.parse(nextQuestion));
        })

        
    };
}



/** remove event listener will be off when the next button is clicked and turned back on 
 * when the person chooses the answer i.e element != null
 */
form.addEventListener("submit",theWholeProcess);

/**Tasks
 * 1. Make the update only work when the next is clicked. 
 * 2. The issue is after the next is clicked, it will also submit the request.
 * Hypothetical fix:
 * when answer is checked remove event listener and add it back when new question is loaded.
 * Why I didn't do it: Didn't know how to pass the event var in the function.
 */