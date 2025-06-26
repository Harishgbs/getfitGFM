const express = require("express");
const mysql = require("mysql2");
const bcrypt = require("bcrypt");
const jwt = require("jsonwebtoken");
const cors = require("cors");
const bodyParser = require("body-parser");

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "yourpassword",
    database: "gym_management"
});

db.connect(err => {
    if (err) console.log("DB Connection Error: " + err);
    else console.log("MySQL Connected");
});

// User Registration
app.post("/register", async (req, res) => {
    const { full_name, email, phone, password, dob, gender, height, weight, fitness_goal, membership_type, preferred_slot } = req.body;
    
    const hashedPassword = await bcrypt.hash(password, 10);
    const sql = "INSERT INTO users (full_name, email, phone, password, dob, gender, height, weight, fitness_goal, membership_type, preferred_slot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    db.query(sql, [full_name, email, phone, hashedPassword, dob, gender, height, weight, fitness_goal, membership_type, preferred_slot], (err, result) => {
        if (err) res.status(500).json({ message: "Database Error", error: err });
        else res.json({ message: "Registration Successful!" });
    });
});

// User Login
app.post("/login", (req, res) => {
    const { email, password } = req.body;
    const sql = "SELECT * FROM users WHERE email = ?";

    db.query(sql, [email], async (err, results) => {
        if (err) return res.status(500).json({ message: "Database Error", error: err });

        if (results.length === 0) return res.status(401).json({ message: "Invalid Email or Password" });

        const user = results[0];
        const passwordMatch = await bcrypt.compare(password, user.password);
        
        if (!passwordMatch) return res.status(401).json({ message: "Invalid Email or Password" });

        const token = jwt.sign({ id: user.id, email: user.email, role: user.role }, "secretkey", { expiresIn: "1h" });
        res.json({ message: "Login Successful", token });
    });
});

// Get User Details
app.get("/user/:id", (req, res) => {
    const sql = "SELECT full_name, email, phone, dob, gender, height, weight, fitness_goal, membership_type, preferred_slot FROM users WHERE id = ?";
    
    db.query(sql, [req.params.id], (err, results) => {
        if (err) res.status(500).json({ message: "Database Error", error: err });
        else res.json(results[0]);
    });
});

// Booking Session
app.post("/book-session", (req, res) => {
    const { user_id, session_date, session_time } = req.body;
    const sql = "INSERT INTO bookings (user_id, session_date, session_time, status) VALUES (?, ?, ?, 'Booked')";

    db.query(sql, [user_id, session_date, session_time], (err, result) => {
        if (err) res.status(500).json({ message: "Database Error", error: err });
        else res.json({ message: "Session Booked!" });
    });
});

// Get Bookings
app.get("/bookings/:user_id", (req, res) => {
    const sql = "SELECT session_date, session_time, status FROM bookings WHERE user_id = ?";

    db.query(sql, [req.params.user_id], (err, results) => {
        if (err) res.status(500).json({ message: "Database Error", error: err });
        else res.json(results);
    });
});

app.listen(5000, () => console.log("Server running on port 5000"));
