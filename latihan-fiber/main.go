package main

import (
	"log"
	"github.com/gofiber/fiber/v3"
)

func main() {
	app := fiber.New()

<<<<<<< HEAD
	app.Get("/", func(c fiber.Ctx) error {
		return c.SendString("Halo Pemrograman Web II")
	})
=======
>>>>>>> 60558f7 (Menyelesaikan Tugas Praktikum Modul 1)

	app.Get("/api/mahasiswa", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{
			"nim":           "H1H024063",
			"nama":          "Fathah Ikhwansyah",
			"program_studi": "Teknik Komputer",
		})
	})

	log.Fatal(app.Listen(":3000"))
}
