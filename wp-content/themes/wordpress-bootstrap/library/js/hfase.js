// animation globals
var t=0; 
var frameInterval = 25; // in ms
var canvas = null; // canvas DOM object
var context = null; // canvas context
 
// ball globals
var ballRadius = 10;
 
// physics globals
var collisionDamper = 0.15;
var floorFriction = 0.0029 * frameInterval;
var mouseForceMultiplier = 0.55 * frameInterval;
var restoreForce =0.0028 * frameInterval;
 
var mouseX = 99999;
var mouseY = 99999;
 
var balls = null;
 
function Ball(x,y,vx,vy,color) {
	this.x=x;
	this.y=y;
	this.vx=vx;
	this.vy=vy;
	this.color=color;
 
	this.origX = x;
	this.origY = y;
}
 
function init() {
	canvas=document.getElementById("myCanvas");
	context=canvas.getContext("2d");
	initStageObjects();
	setInterval(updateStage, frameInterval);
}
 
function updateStage() {
	t+=frameInterval; 
	clearCanvas();
	updateStageObjects();
	drawStageObjects();	
}
 
function initStageObjects() {
	balls = new Array();
 
	var green = "#3A5BCD";
	var black = "#000000";
	var red = "#FF0000";
	
	balls.push(new Ball(100,20,0,0,black));
	balls.push(new Ball(100,25,0,0,black));
	balls.push(new Ball(100,30,0,0,black));
	balls.push(new Ball(100,35,0,0,black));
	balls.push(new Ball(100,40,0,0,black));
	balls.push(new Ball(100,45,0,0,black));
	balls.push(new Ball(100,50,0,0,black));
	balls.push(new Ball(100,55,0,0,black));
	balls.push(new Ball(100,60,0,0,black));
	balls.push(new Ball(100,65,0,0,black));
	balls.push(new Ball(100,70,0,0,black));
	balls.push(new Ball(100,75,0,0,black));
	balls.push(new Ball(100,80,0,0,black));
	balls.push(new Ball(100,85,0,0,black));
	balls.push(new Ball(100,90,0,0,black));
	balls.push(new Ball(100,95,0,0,black));
	balls.push(new Ball(100,100,0,0,black));
	balls.push(new Ball(100,105,0,0,black));
	balls.push(new Ball(100,110,0,0,black));
	balls.push(new Ball(100,115,0,0,black));
	balls.push(new Ball(100,120,0,0,black));
	balls.push(new Ball(100,125,0,0,black));
	balls.push(new Ball(100,130,0,0,black));
	balls.push(new Ball(100,135,0,0,black));
	balls.push(new Ball(100,140,0,0,black));
	balls.push(new Ball(100,145,0,0,black));
	balls.push(new Ball(100,150,0,0,black));
	balls.push(new Ball(100,155,0,0,black));
	balls.push(new Ball(150,20,0,0,black));
	balls.push(new Ball(150,25,0,0,black));
	balls.push(new Ball(150,30,0,0,black));
	balls.push(new Ball(150,35,0,0,black));
	balls.push(new Ball(150,40,0,0,black));
	balls.push(new Ball(150,45,0,0,black));
	balls.push(new Ball(150,50,0,0,black));
	balls.push(new Ball(150,55,0,0,black));
	balls.push(new Ball(150,60,0,0,black));
	balls.push(new Ball(150,65,0,0,black));
	balls.push(new Ball(150,70,0,0,black));
	balls.push(new Ball(150,75,0,0,black));
	balls.push(new Ball(150,80,0,0,black));
	balls.push(new Ball(150,85,0,0,black));
	balls.push(new Ball(150,90,0,0,black));
	balls.push(new Ball(150,95,0,0,black));
	balls.push(new Ball(150,100,0,0,black));
	balls.push(new Ball(150,105,0,0,black));
	balls.push(new Ball(150,110,0,0,black));
	balls.push(new Ball(150,115,0,0,black));
	balls.push(new Ball(150,120,0,0,black));
	balls.push(new Ball(150,125,0,0,black));
	balls.push(new Ball(150,130,0,0,black));
	balls.push(new Ball(150,135,0,0,black));
	balls.push(new Ball(150,140,0,0,black));
	balls.push(new Ball(150,145,0,0,black));
	balls.push(new Ball(150,150,0,0,black));
	balls.push(new Ball(150,155,0,0,black));
	balls.push(new Ball(100,100,0,0,black));
	balls.push(new Ball(105,100,0,0,black));
	balls.push(new Ball(110,100,0,0,black));
	balls.push(new Ball(115,100,0,0,black));
	balls.push(new Ball(120,100,0,0,black));
	balls.push(new Ball(125,100,0,0,black));
	balls.push(new Ball(130,100,0,0,black));
	balls.push(new Ball(135,100,0,0,black));
	balls.push(new Ball(140,100,0,0,black));
	balls.push(new Ball(145,100,0,0,black));	
	balls.push(new Ball(150,100,0,0,black));
	balls.push(new Ball(155,100,0,0,black));
	balls.push(new Ball(160,100,0,0,black));
	balls.push(new Ball(165,100,0,0,black));
	balls.push(new Ball(170,100,0,0,black));
	balls.push(new Ball(175,100,0,0,black));
	balls.push(new Ball(180,100,0,0,black));
	balls.push(new Ball(185,100,0,0,black));
	balls.push(new Ball(190,100,0,0,black));
	balls.push(new Ball(150,20,0,0,black));
	balls.push(new Ball(155,20,0,0,black));
	balls.push(new Ball(160,20,0,0,black));
	balls.push(new Ball(165,20,0,0,black));
	balls.push(new Ball(170,20,0,0,black));
	balls.push(new Ball(175,20,0,0,black));
	balls.push(new Ball(180,20,0,0,black));
	balls.push(new Ball(185,20,0,0,black));
	balls.push(new Ball(190,20,0,0,black));
	balls.push(new Ball(195,20,0,0,black));
	balls.push(new Ball(200,20,0,0,black));
	balls.push(new Ball(205,20,0,0,black));
	balls.push(new Ball(210,20,0,0,black));
	balls.push(new Ball(215,20,0,0,black));	
	balls.push(new Ball(180,60,0,0,black));
	balls.push(new Ball(180,65,0,0,green));
	balls.push(new Ball(180,70,0,0,green));
	balls.push(new Ball(180,75,0,0,green));
	balls.push(new Ball(180,80,0,0,green));
	balls.push(new Ball(180,85,0,0,green));
	balls.push(new Ball(180,90,0,0,green));
	balls.push(new Ball(180,95,0,0,green));
	balls.push(new Ball(180,100,0,0,green));
	balls.push(new Ball(180,105,0,0,green));
	balls.push(new Ball(180,110,0,0,green));
	balls.push(new Ball(180,115,0,0,green));
	balls.push(new Ball(180,120,0,0,green));
	balls.push(new Ball(180,125,0,0,green));
	balls.push(new Ball(180,130,0,0,green));
	balls.push(new Ball(180,135,0,0,green));
	balls.push(new Ball(180,140,0,0,green));
	balls.push(new Ball(180,145,0,0,green));
	balls.push(new Ball(180,150,0,0,green));
	balls.push(new Ball(180,155,0,0,green));	
	balls.push(new Ball(180,60,0,0,green));
	balls.push(new Ball(185,60,0,0,green));
	balls.push(new Ball(190,60,0,0,green));
	balls.push(new Ball(195,60,0,0,green));
	balls.push(new Ball(200,60,0,0,green));
	balls.push(new Ball(205,60,0,0,green));
	balls.push(new Ball(210,60,0,0,green));
	balls.push(new Ball(215,60,0,0,green));
	balls.push(new Ball(220,60,0,0,green));	
	balls.push(new Ball(225,60,0,0,green));
	balls.push(new Ball(225,65,0,0,green));
	balls.push(new Ball(225,70,0,0,green));
	balls.push(new Ball(225,75,0,0,green));
	balls.push(new Ball(225,80,0,0,green));
	balls.push(new Ball(225,85,0,0,green));
	balls.push(new Ball(225,90,0,0,green));
	balls.push(new Ball(225,95,0,0,green));
	balls.push(new Ball(225,100,0,0,green));	
	balls.push(new Ball(225,105,0,0,green));
	balls.push(new Ball(225,110,0,0,green));
	balls.push(new Ball(225,115,0,0,green));
	balls.push(new Ball(225,120,0,0,green));
	balls.push(new Ball(225,125,0,0,green));
	balls.push(new Ball(225,130,0,0,green));
	balls.push(new Ball(225,135,0,0,green));
	balls.push(new Ball(225,140,0,0,green));
	balls.push(new Ball(225,145,0,0,green));
	balls.push(new Ball(225,150,0,0,green));
	balls.push(new Ball(225,155,0,0,green));
	balls.push(new Ball(255,60,0,0,green));
	balls.push(new Ball(260,60,0,0,green));
	balls.push(new Ball(265,60,0,0,green));
	balls.push(new Ball(270,60,0,0,green));
	balls.push(new Ball(275,60,0,0,green));
	balls.push(new Ball(280,60,0,0,green));
	balls.push(new Ball(285,60,0,0,green));
	balls.push(new Ball(290,60,0,0,green));
	balls.push(new Ball(255,60,0,0,green));
	balls.push(new Ball(255,65,0,0,green));
	balls.push(new Ball(255,70,0,0,green));
	balls.push(new Ball(255,75,0,0,green));
	balls.push(new Ball(255,80,0,0,green));
	balls.push(new Ball(255,85,0,0,green));
	balls.push(new Ball(255,90,0,0,green));
	balls.push(new Ball(255,95,0,0,green));
	balls.push(new Ball(255,100,0,0,green));	
	balls.push(new Ball(255,105,0,0,green));
	balls.push(new Ball(260,105,0,0,green));
	balls.push(new Ball(265,105,0,0,green));
	balls.push(new Ball(270,105,0,0,green));
	balls.push(new Ball(275,105,0,0,green));
	balls.push(new Ball(280,105,0,0,green));
	balls.push(new Ball(285,105,0,0,green));
	balls.push(new Ball(290,105,0,0,green));
	balls.push(new Ball(290,110,0,0,green));
	balls.push(new Ball(290,115,0,0,green));
	balls.push(new Ball(290,120,0,0,green));
	balls.push(new Ball(290,125,0,0,green));
	balls.push(new Ball(290,130,0,0,green));
	balls.push(new Ball(290,135,0,0,green));
	balls.push(new Ball(290,140,0,0,green));
	balls.push(new Ball(290,145,0,0,green));
	balls.push(new Ball(290,150,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(255,155,0,0,green));
	balls.push(new Ball(260,155,0,0,green));
	balls.push(new Ball(265,155,0,0,green));
	balls.push(new Ball(270,155,0,0,green));
	balls.push(new Ball(275,155,0,0,green));
	balls.push(new Ball(280,155,0,0,green));
	balls.push(new Ball(285,155,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(320,60,0,0,green));
	balls.push(new Ball(325,60,0,0,green));
	balls.push(new Ball(330,60,0,0,green));
	balls.push(new Ball(335,60,0,0,green));
	balls.push(new Ball(340,60,0,0,green));
	balls.push(new Ball(345,60,0,0,green));
	balls.push(new Ball(350,60,0,0,green));
	balls.push(new Ball(355,60,0,0,green));
	balls.push(new Ball(320,65,0,0,green));
	balls.push(new Ball(320,70,0,0,green));
	balls.push(new Ball(320,75,0,0,green));
	balls.push(new Ball(320,80,0,0,green));
	balls.push(new Ball(320,85,0,0,green));
	balls.push(new Ball(320,90,0,0,green));
	balls.push(new Ball(320,95,0,0,green));
	balls.push(new Ball(320,100,0,0,green));
	balls.push(new Ball(320,105,0,0,green));
	balls.push(new Ball(320,110,0,0,green));
	balls.push(new Ball(320,115,0,0,green));
	balls.push(new Ball(320,120,0,0,green));
	balls.push(new Ball(320,125,0,0,green));
	balls.push(new Ball(320,130,0,0,green));
	balls.push(new Ball(320,135,0,0,green));
	balls.push(new Ball(320,140,0,0,green));
	balls.push(new Ball(320,145,0,0,green));
	balls.push(new Ball(320,150,0,0,green));
	balls.push(new Ball(320,155,0,0,green));
	balls.push(new Ball(320,100,0,0,green));
	balls.push(new Ball(325,100,0,0,green));
	balls.push(new Ball(330,100,0,0,green));
	balls.push(new Ball(335,100,0,0,green));
	balls.push(new Ball(340,100,0,0,green));
	balls.push(new Ball(345,100,0,0,green));
	balls.push(new Ball(350,100,0,0,green));
	balls.push(new Ball(355,100,0,0,green));
	balls.push(new Ball(320,155,0,0,green));
	balls.push(new Ball(325,155,0,0,green));
	balls.push(new Ball(330,155,0,0,green));
	balls.push(new Ball(335,155,0,0,green));
	balls.push(new Ball(340,155,0,0,green));
	balls.push(new Ball(345,155,0,0,green));
	balls.push(new Ball(350,155,0,0,green));
	balls.push(new Ball(355,155,0,0,green));
	balls.push(new Ball(195,100,0,0,red));
}
 
function drawStageObjects() {
	for (var n=0; n<balls.length; n++) {	
		context.beginPath();
		context.arc(balls[n].x,balls[n].y,ballRadius,
			0,2*Math.PI,false);
		context.fillStyle=balls[n].color;
		context.fill();20
	}
 
 
}
 
function updateStageObjects() {
 
	for (var n=0; n<balls.length; n++) {
 
		// set ball position based on velocity
		balls[n].y+=balls[n].vy;
		balls[n].x+=balls[n].vx;
 
		// restore forces
 
 
 
		if (balls[n].x > balls[n].origX) {
			balls[n].vx -= restoreForce;
		}
		else {
			balls[n].vx += restoreForce;
		}
		if (balls[n].y > balls[n].origY) {
			balls[n].vy -= restoreForce;
		}
		else {
			balls[n].vy += restoreForce;
		}
 
 
 
		// mouse forces
		var distX = balls[n].x - mouseX;
		var distY = balls[n].y - mouseY;
 
		var radius = Math.sqrt(Math.pow(distX,2) + 
			Math.pow(distY,2));
 
		var totalDist = Math.abs(distX) + Math.abs(distY);
 
		var forceX = (Math.abs(distX) / totalDist) * 
			(1/radius) * mouseForceMultiplier;
		var forceY = (Math.abs(distY) / totalDist) * 
			(1/radius) * mouseForceMultiplier;
 
		if (distX>0) { // mouse is left of ball
			balls[n].vx += forceX;
		}
		else {
			balls[n].vx -= forceX;
		}
		if (distY>0) { // mouse is on top of ball
			balls[n].vy += forceY;
		}
		else {
			balls[n].vy -= forceY;
		}
 
 
		// floor friction
		if (balls[n].vx>0) {
			balls[n].vx-=floorFriction;
		}
		else if (balls[n].vx<0) {
			balls[n].vx+=floorFriction;
		}
		if (balls[n].vy>0) {
			balls[n].vy-=floorFriction;
		}
		else if (balls[n].vy<0) {
			balls[n].vy+=floorFriction;
		}
 
		// floor condition
		if (balls[n].y > (canvas.height-ballRadius)) {
			balls[n].y=canvas.height-ballRadius-2;
			balls[n].vy*=-1; 
			balls[n].vy*=(1-collisionDamper);
		}
 
		// ceiling condition
		if (balls[n].y < (ballRadius)) {
			balls[n].y=ballRadius+2;
			balls[n].vy*=-1; 
			balls[n].vy*=(1-collisionDamper);
		}
 
		// right wall condition
		if (balls[n].x > (canvas.width-ballRadius)) {
			balls[n].x=canvas.width-ballRadius-2;
			balls[n].vx*=-1;
			balls[n].vx*=(1-collisionDamper);
		}
 
		// left wall condition
		if (balls[n].x < (ballRadius)) {
			balls[n].x=ballRadius+2;
			balls[n].vx*=-1;
			balls[n].vx*=(1-collisionDamper);
		}	
	}
}
 
function clearCanvas() {
	context.clearRect(0,0,canvas.width, canvas.height);
}
 
function handleMouseMove(evt) {
	mouseX = evt.clientX - canvas.offsetLeft;
	mouseY = evt.clientY - canvas.offsetTop;	
}
 
function handleMouseOut() {
	mouseX = 99999;
	mouseY = 99999;
}