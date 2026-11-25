# CRM

Kichik CRM tizimi. Foydalanuvchilar, kompaniyalar va mijozlarni boshqarish mumkin.

- **Frontend** — Vue 3, Vue Router, Axios (`frontend/` papkasi)
- **Backend** — Symfony 8, Doctrine ORM (`backend/` papkasi)
- **Baza** — MySQL 8

## Imkoniyatlar

- Tizimga email va parol bilan kirish
- Foydalanuvchi qo'shish, o'zgartirish, o'chirish (qo'shilgan foydalanuvchi tizimga kira oladi)
- Kompaniya qo'shish, o'zgartirish, o'chirish
- Mijoz qo'shish, o'zgartirish, o'chirish. Mijoz qo'shilayotganda qaysi kompaniyada ishlashi tanlanadi
- Har bir sahifada ism, email va so'ngi faollik bo'yicha qidiruv
- Mijozlarni ishlash joyi bo'yicha qidirish va kompaniya bo'yicha filtrlash

Parollar bazada hash qilingan holda saqlanadi, shuning uchun jadvalda `********` ko'rinishida chiqadi.

## Ishga tushirish

Kompyuterda Docker va Node.js o'rnatilgan bo'lishi kerak.

### Backend

```
docker compose up -d --build
```

Birinchi marta ishga tushganda composer paketlarni o'rnatadi va migratsiyalarni bajaradi, biroz kutish kerak. Server `http://localhost:8000` da ishlaydi.

Test ma'lumotlarini yuklash:

```
docker compose exec backend php bin/console doctrine:fixtures:load
```

### Frontend

```
cd frontend
npm install
npm run dev
```

Sayt `http://localhost:5173` da ochiladi.

## Kirish uchun

Test ma'lumotlari yuklangandan keyin:

- Email: `ameliya.cer@gmail.com`
- Parol: `12345678`
