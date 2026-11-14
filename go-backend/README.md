# Go Books API — الواجهة الخلفية (Backend)

تطبيق REST API بسيط مكتوب بلغة **Go** باستخدام المكتبة القياسية فقط (`net/http` + `encoding/json`)، ويحفظ البيانات في الذاكرة (In-Memory) لتبسيط التجربة.

## التشغيل

```bash
cd go-backend
go run .
```

يعمل على المنفذ `8080` افتراضياً، ويمكن تغييره:

```powershell
$env:PORT=9090; go run .
```

## نقاط النهاية (Endpoints)

| Method | Endpoint          | الوظيفة              | Body (JSON)                              |
| ------ | ----------------- | -------------------- | ---------------------------------------- |
| GET    | `/health`         | فحص عمل الخادم        | —                                        |
| GET    | `/api/books`      | جلب كل الكتب         | —                                        |
| GET    | `/api/books/{id}` | جلب كتاب واحد        | —                                        |
| POST   | `/api/books`      | إضافة كتاب           | `{"title":"...","author":"...","year":2020}` |
| PUT    | `/api/books/{id}` | تعديل كتاب           | نفس الحقول أعلاه                          |
| DELETE | `/api/books/{id}` | حذف كتاب             | —                                        |

### أمثلة باستخدام curl

```bash
curl http://localhost:8080/api/books

curl -X POST http://localhost:8080/api/books \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"title":"Refactoring","author":"Martin Fowler","year":2018}'

curl -X DELETE http://localhost:8080/api/books/1
```

### شكل الاستجابة

```json
{
  "data": [
    {
      "id": 1,
      "title": "The Go Programming Language",
      "author": "Alan Donovan",
      "year": 2015,
      "created_at": "2026-10-31T10:00:00Z"
    }
  ],
  "count": 1
}
```

وعند الخطأ:

```json
{ "error": "عنوان الكتاب مطلوب" }
```

## الاختبارات

```bash
cd go-backend
go test ./...
```

## ملاحظات تعليمية

- البيانات **في الذاكرة**: إعادة تشغيل الخادم تعيد البيانات الأولية (3 كتب).
- `sync.RWMutex` يحمي البيانات من التعديل المتزامن بين الطلبات.
- `withLogging` يطبع كل طلب في الطرفية لتتابع ما يرسله Laravel فعلياً.
- `withCORS` يسمح لأي واجهة أخرى بالاتصال بالـ API.
